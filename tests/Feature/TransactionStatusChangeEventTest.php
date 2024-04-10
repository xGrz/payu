<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Events\TransactionCanceled;
use xGrz\PayU\Events\TransactionCompleted;
use xGrz\PayU\Events\TransactionCreated;
use xGrz\PayU\Events\TransactionPaid;
use xGrz\PayU\Events\TransactionPending;
use xGrz\PayU\Models\Transaction;

class TransactionStatusChangeEventTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake([
            TransactionCreated::class,
            TransactionPending::class,
            TransactionPaid::class,
            TransactionCompleted::class,
            TransactionCanceled::class
        ]);

    }

    private function createTransaction(PaymentStatus $paymentStatus = PaymentStatus::INITIALIZED, bool $createEventSilenced = true): Transaction
    {
        $transaction = new Transaction([
            'payu_order_id' => 'AIDJAODJAODJAOIJD',
            'link' => 'https://payu.com',
            'payload' => [],
            'status' => PaymentStatus::INITIALIZED
        ]);
        $transaction
            ->when(
                $createEventSilenced,
                fn() => $transaction->saveQuietly(),
                fn() => $transaction->save()
            );
        return $transaction;
    }

    public function test_is_transaction_event_dispatched_after_created_new_transaction()
    {
        self::createTransaction(createEventSilenced: false);

        Event::assertDispatched(TransactionCreated::class);
        Event::assertNotDispatched(TransactionPending::class);
        Event::assertNotDispatched(TransactionPaid::class);
        Event::assertNotDispatched(TransactionCompleted::class);
        Event::assertNotDispatched(TransactionCanceled::class);
    }

    public function test_is_transaction_event_dispatched_after_status_changed_to_pending()
    {
        $transaction = self::createTransaction();
        $transaction->update(['status' => PaymentStatus::PENDING]);

        Event::assertNotDispatched(TransactionCreated::class);
        Event::assertDispatched(TransactionPending::class);
        Event::assertNotDispatched(TransactionPaid::class);
        Event::assertNotDispatched(TransactionCompleted::class);
        Event::assertNotDispatched(TransactionCanceled::class);
    }

    public function test_is_transaction_event_dispatched_after_status_changed_to_paid()
    {
        $transaction = self::createTransaction();
        $transaction->update(['status' => PaymentStatus::WAITING_FOR_CONFIRMATION]);

        Event::assertNotDispatched(TransactionCreated::class);
        Event::assertNotDispatched(TransactionPending::class);
        Event::assertDispatched(TransactionPaid::class);
        Event::assertNotDispatched(TransactionCompleted::class);
        Event::assertNotDispatched(TransactionCanceled::class);
    }

    public function test_is_transaction_event_dispatched_after_status_changed_to_completed()
    {
        $transaction = self::createTransaction();
        $transaction->update(['status' => PaymentStatus::COMPLETED]);

        Event::assertNotDispatched(TransactionCreated::class);
        Event::assertNotDispatched(TransactionPending::class);
        Event::assertNotDispatched(TransactionPaid::class);
        Event::assertDispatched(TransactionCompleted::class);
        Event::assertNotDispatched(TransactionCanceled::class);
    }

    public function test_is_transaction_event_dispatched_after_status_changed_to_canceled()
    {
        $transaction = self::createTransaction();
        $transaction->update(['status' => PaymentStatus::CANCELED]);

        Event::assertNotDispatched(TransactionCreated::class);
        Event::assertNotDispatched(TransactionPending::class);
        Event::assertNotDispatched(TransactionPaid::class);
        Event::assertNotDispatched(TransactionCompleted::class);
        Event::assertDispatched(TransactionCanceled::class);
    }

}


