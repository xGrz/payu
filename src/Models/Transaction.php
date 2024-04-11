<?php

namespace xGrz\PayU\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use xGrz\PayU\Casts\Amount;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'payu_transactions';

    protected $casts = [
        'status' => PaymentStatus::class,
        'amount' => Amount::class,
        'payload' => 'array',
    ];

    protected $with = ['refunds', 'payMethod'];

    protected $guarded = [];

    public function payMethod(): BelongsTo
    {
        return $this->belongsTo(Method::class, 'method_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'transaction_id')->latest();
    }

    public function refundedAmount(): int
    {
        return $this
            ->refunds
            ->whereIn('status', RefundStatus::withAction('success'))
            ->sum('amount');
    }

    public function hasSuccessfulRefunds(): bool
    {
        return (bool)$this->refundedAmount();
    }

    public function hasDefinedRefunds(): bool
    {
        return $this
            ->refunds
            ->whereNotIn('status', RefundStatus::withAction('success'))
            ->whereNotIn('status', RefundStatus::withAction('failed'))
            ->count();
    }

    public function getDefinedRefundsTotalAmount(): int|float
    {
        return $this
            ->refunds
            ->whereNotIn('status', RefundStatus::withAction('success'))
            ->whereNotIn('status', RefundStatus::withAction('failed'))
            ->sum('amount');
    }

    public function hasFailedRefunds(): bool
    {
        return $this
            ->refunds
            ->whereIn('status', RefundStatus::withAction('failed'))
            ->count();
    }

    public function getFailedRefundsTotalAmount(): int|float
    {
        return $this
            ->refunds
            ->whereIn('status', RefundStatus::withAction('failed'))
            ->sum('amount');
    }

    public function maxRefundAmount(): float|int
    {
        return ($this->amount / 100) - $this->refundedAmount();
    }

    public function isRefundAvailable(): bool
    {
        if (!$this->status->hasAction('refund')) return false;
        return self::maxRefundAmount() > 0;
    }

    public function payuable(): MorphTo
    {
        return $this->morphTo();
    }


}
