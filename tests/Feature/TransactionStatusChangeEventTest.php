<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Traits\WithTransactionModel;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Events\TransactionCanceled;
use xGrz\PayU\Events\TransactionCompleted;
use xGrz\PayU\Events\TransactionCreated;
use xGrz\PayU\Events\TransactionPaid;
use xGrz\PayU\Events\TransactionPending;

class TransactionStatusChangeEventTest extends TestCase
{
    use RefreshDatabase;
    use WithTransactionModel;

    public array $events = [
        TransactionCreated::class,
        TransactionPending::class,
        TransactionPaid::class,
        TransactionCompleted::class,
        TransactionCanceled::class
    ];

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake($this->events);
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


