<?php

require_once(__DIR__ . '/../Traits/WithTransactionModel.php');

use Tests\TestCase;
use Traits\WithTransactionModel;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Services\ConfigService;

class TransactionModelTest extends TestCase
{
    use WithTransactionModel;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('payu.api.use_sandbox', true);
        Config::set('payu.api.oAuthClientId', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_ID']);
        Config::set('payu.api.oAuthClientSecret', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_SECRET']);
    }

    public function test_transaction_without_refunds_has_refunds()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        $this->assertFalse($transaction->hasSuccessfulRefunds());
    }

    public function test_transaction_without_refunds_has_defined_refunds()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        $this->assertFalse($transaction->hasDefinedRefunds());
    }

    public function test_transaction_without_refunds_has_failed_refunds()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        $this->assertFalse($transaction->hasFailedRefunds());
    }

    public function test_transaction_without_refunds_max_refund_amount()
    {
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        $this->assertEquals(1000, $transaction->maxRefundAmount());
    }

    public function test_transaction_can_be_refunded_when_not_completed(): void
    {
        $transaction = self::createTransaction(PaymentStatus::INITIALIZED, true);
        $this->assertFalse($transaction->isRefundAvailable());

        $transaction = self::createTransaction(PaymentStatus::NEW, true);
        $this->assertFalse($transaction->isRefundAvailable());

        $transaction = self::createTransaction(PaymentStatus::PENDING, true);
        $this->assertFalse($transaction->isRefundAvailable());

        $transaction = self::createTransaction(PaymentStatus::WAITING_FOR_CONFIRMATION, true);
        $this->assertFalse($transaction->isRefundAvailable());

        $transaction = self::createTransaction(PaymentStatus::CANCELED, true);
        $this->assertFalse($transaction->isRefundAvailable());
    }

    public function test_transaction_has_successful_refunds(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::FINALIZED);
        self::addRefund($transaction, RefundStatus::ERROR);

        $this->assertTrue($transaction->hasSuccessfulRefunds());
    }


    public function test_transaction_has_failed_refunds_when_has_refund_with_error_and_success(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::FINALIZED);
        self::addRefund($transaction, RefundStatus::ERROR);

        $this->assertTrue($transaction->hasFailedRefunds());
    }

    public function test_transaction_has_failed_refunds_when_has_refund_with_error(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::ERROR);

        $this->assertTrue($transaction->hasFailedRefunds());
    }

    public function test_transaction_has_failed_refunds_when_has_refund_with_success_only(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::FINALIZED);

        $this->assertFalse($transaction->hasFailedRefunds());
    }

    public function test_max_refund_amount_when_no_refunds_defined(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        $this->assertEquals(1000, $transaction->maxRefundAmount());
    }

    public function test_max_refund_amount_when_transaction_has_failed_refunds(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::ERROR);
        $this->assertEquals(1000, $transaction->maxRefundAmount());
    }

    public function test_max_refund_amount_when_transaction_has_processing_refunds(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::SENT); // 100 refunded
        self::addRefund($transaction, RefundStatus::PENDING); // 100 refunded
        $this->assertEquals(800, $transaction->maxRefundAmount());
    }

    public function test_max_refund_amount_when_transaction_has_successful_refunds(): void
    {
        Event::fake();
        $transaction = self::createTransaction(PaymentStatus::COMPLETED, true);
        self::addRefund($transaction, RefundStatus::FINALIZED); // 100 refunded
        $this->assertEquals(900, $transaction->maxRefundAmount());
    }

}


