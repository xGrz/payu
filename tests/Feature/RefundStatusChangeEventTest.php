<?php

require_once __DIR__ . '/../Traits/WithTransactionModel.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Traits\WithTransactionModel;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCompleted;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Events\RefundDeleted;
use xGrz\PayU\Events\RefundFailed;
use xGrz\PayU\Facades\PayU;

class RefundStatusChangeEventTest extends TestCase
{
    use RefreshDatabase;
    use WithTransactionModel;

    public array $events = [
        RefundCreated::class,
        RefundCompleted::class,
        RefundFailed::class,
        RefundDeleted::class,
    ];

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake($this->events);
    }

    public function test_is_refund_event_is_dispatched_after_created_new_refund()
    {
        $transaction = self::createTransaction();
        $refund = self::addRefund($transaction);

        Event::assertDispatched(RefundCreated::class, function ($event) use ($refund) {
            return $event->refund->id === $refund->id;
        });
        Event::assertNotDispatched(RefundCompleted::class);
        Event::assertNotDispatched(RefundFailed::class);
        Event::assertNotDispatched(RefundDeleted::class);
    }

    public function test_is_refund_completed_event_dispatched_after_status_changed_to_completed()
    {
        $transaction = self::createTransaction();
        $refund = self::addRefund($transaction, createEventSilenced: true);
        $refund->update(['status' => RefundStatus::FINALIZED]);

        Event::assertNotDispatched(RefundCreated::class);
        Event::assertDispatched(RefundCompleted::class, function ($event) use ($refund) {
            return $event->refund->id === $refund->id;
        });
        Event::assertNotDispatched(RefundFailed::class);
        Event::assertNotDispatched(RefundDeleted::class);
    }

    public function test_is_refund_failed_event_dispatched_after_status_changed_to_cancel()
    {
        $transaction = self::createTransaction();
        $refund = self::addRefund($transaction, createEventSilenced: true);
        $refund->update(['status' => RefundStatus::CANCELED]);

        Event::assertNotDispatched(RefundCreated::class);
        Event::assertNotDispatched(RefundCompleted::class,);
        Event::assertDispatched(RefundFailed::class, function ($event) use ($refund) {
            return $event->refund->id === $refund->id;
        });
        Event::assertNotDispatched(RefundDeleted::class);
    }

    public function test_is_refund_failed_event_dispatched_after_status_changed_to_error()
    {
        $transaction = self::createTransaction();
        $refund = self::addRefund($transaction, createEventSilenced: true);
        $refund->update(['status' => RefundStatus::ERROR]);

        Event::assertNotDispatched(RefundCreated::class);
        Event::assertNotDispatched(RefundCompleted::class,);
        Event::assertDispatched(RefundFailed::class, function ($event) use ($refund) {
            return $event->refund->id === $refund->id;
        });
        Event::assertNotDispatched(RefundDeleted::class);
    }

    public function test_is_refund_deleted_event_dispatched_after_refund_is_removed()
    {
        $transaction = self::createTransaction();
        $refund = self::addRefund($transaction, createEventSilenced: true);
        PayU::cancelRefund($refund);

        Event::assertNotDispatched(RefundCreated::class);
        Event::assertNotDispatched(RefundCompleted::class,);
        Event::assertNotDispatched(RefundFailed::class );
        Event::assertDispatched(RefundDeleted::class, function ($event) use ($refund) {
            return $event->refund->id === $refund->id;
        });
    }

}


