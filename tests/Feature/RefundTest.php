<?php

require_once __DIR__ . '/../Traits/WithTransactionModel.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Traits\WithTransactionModel;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Jobs\SendRefundJob;
use xGrz\PayU\Listeners\DispatchRefundRequest;
use xGrz\PayU\Services\ConfigService;

class RefundTest extends TestCase
{
    use RefreshDatabase;
    use WithTransactionModel;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('payu.api.use_sandbox', true);
        Config::set('payu.api.oAuthClientId', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_ID']);
        Config::set('payu.api.oAuthClientSecret', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_SECRET']);
    }

    public function test_can_create_refund_when_transaction_completed(): void
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        $refund = PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        $this->assertTrue($refund);
        $this->assertDatabaseHas('payu_refunds', [
            'transaction_id' => $transaction->id,
            'amount' => 2000,
            'description' => 'RMA',
            'bank_description' => 'RMA-Bank',
            'currency_code' => 'PLN'
        ]);
    }

    public function test_cannot_create_refund_to_initialized_transaction()
    {
        $transaction = self::createTransaction(PaymentStatus::INITIALIZED);
        $refund = PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        $this->assertFalse($refund);
    }

    public function test_cannot_create_refund_to_new_transaction()
    {
        $transaction = self::createTransaction(PaymentStatus::NEW);
        $refund = PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        $this->assertFalse($refund);
    }

    public function test_cannot_create_refund_to_pending_transaction()
    {
        $transaction = self::createTransaction(PaymentStatus::PENDING);
        $refund = PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        $this->assertFalse($refund);
    }

    public function test_cannot_create_refund_to_waiting_for_confirmation_transaction()
    {
        $transaction = self::createTransaction(PaymentStatus::WAITING_FOR_CONFIRMATION);
        $refund = PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        $this->assertFalse($refund);
    }

    public function test_cannot_create_refund_to_canceled_transaction()
    {
        $transaction = self::createTransaction(PaymentStatus::CANCELED);
        $refund = PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        $this->assertFalse($refund);
    }

    public function test_create_refund_event_is_generated_and_listened_by_refund_dispatcher()
    {
        Http::fake();
        Event::fake([
            RefundCreated::class
        ]);
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');

        Event::assertDispatched(RefundCreated::class, function ($event) use ($transaction) {
            return $event->refund->id === $transaction->refunds()->latest()->first()->id;
        });

        Event::assertListening(
            RefundCreated::class,
            DispatchRefundRequest::class
        );
    }

    public function test_refund_dispatcher_is_creating_send_refund_job()
    {
        Event::fake([
            RefundCreated::class,
        ]);
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');
        $event = new RefundCreated($transaction->refunds()->latest()->first());
        $listener = new DispatchRefundRequest();

        $this->assertDatabaseHas('payu_refunds', [
            'transaction_id' => $transaction->id,
            'status' => RefundStatus::INITIALIZED,
            'amount' => 2000
        ]);

        Queue::fake([
            SendRefundJob::class
        ]);
        $listener->handle($event);

        $this->assertDatabaseHas('payu_refunds', [
            'transaction_id' => $transaction->id,
            'status' => RefundStatus::SCHEDULED,
        ]);

        Queue::assertPushed(SendRefundJob::class);
    }

    public function test_send_refund_job_is_sending_request_to_payu()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');
        $refund = $transaction->refunds()->latest()->first();
        $refund->update(['status' => RefundStatus::SCHEDULED]);

        Queue::fake([SendRefundJob::class]);
        Http::fake([
            'https://secure.snd.payu.com/*' => Http::response([
                'orderId' => $refund->transaction->payu_order_id,
                'refund' => [
                    'refundId' => 'ADPDKAPDPAKD',
                    'extRefundId' => 'adasdasdasddas',
                    'amount' => 2000,
                    'currencyCode' => 'PLN',
                    'description' => 'RMA',
                    'bank_description' => 'RMA-Bank',
                    'status' => 'PENDING'
                ]
            ])
        ]);

        (new SendRefundJob($refund))->handle();
        Http::assertSentCount(1);
        $this->assertDatabaseHas('payu_refunds', [
            'transaction_id' => $transaction->id,
            'status' => RefundStatus::PENDING,
        ]);
    }

    public function test_is_refund_sent_to_payu_as_retry()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');
        $refund = $transaction->refunds()->latest()->first();
        $refund->update(['status' => RefundStatus::ERROR]);
        Http::fake();
        Queue::fake();

        $retry = PayU::retryRefund($refund);

        $this->assertTrue($retry);

        $this->assertDatabaseHas('payu_refunds', [
            'transaction_id' => $transaction->id,
            'status' => RefundStatus::RETRY,
        ]);

        Queue::assertPushed(SendRefundJob::class);
    }

    public function test_can_cancel_refund_before_sent_to_payu()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');
        $refund = $transaction->refunds()->latest()->first();
        $refund->update(['status' => RefundStatus::SCHEDULED]);

        $this->assertTrue( PayU::cancelRefund($refund));
    }

    public function test_can_delete_refund_when_error_occurred()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');
        $refund = $transaction->refunds()->latest()->first();

        $refund->update(['status' => RefundStatus::ERROR]);
        $this->assertTrue( PayU::cancelRefund($refund));

    }

    public function test_cannot_cancel_refund_after_send()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED);
        PayU::refund($transaction, 20, 'RMA', 'RMA-Bank', 'PLN');
        $refund = $transaction->refunds()->latest()->first();

        $refund->update(['status' => RefundStatus::SENT]);
        $this->assertFalse( PayU::cancelRefund($refund));

        $refund->update(['status' => RefundStatus::CANCELED]);
        $this->assertFalse( PayU::cancelRefund($refund));

        $refund->update(['status' => RefundStatus::PENDING]);
        $this->assertFalse( PayU::cancelRefund($refund));

        $refund->update(['status' => RefundStatus::FINALIZED]);
        $this->assertFalse( PayU::cancelRefund($refund));

        $refund->update(['status' => RefundStatus::RETRY]);
        $this->assertFalse( PayU::cancelRefund($refund));

    }

}


