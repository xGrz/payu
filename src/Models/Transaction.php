<?php

namespace xGrz\PayU\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function refunded(): int
    {
        return $this
            ->refunds
            ->whereIn('status', RefundStatus::withAction('success'))
            ->sum('amount');
    }

    public function hasRefunds(): bool
    {
        return (bool)$this->refunded();
    }

    public function hasDefinedRefunds()
    {
        return $this
            ->refunds
            ->whereNotIn('status', RefundStatus::withAction('success'))
            ->whereNotIn('status', RefundStatus::withAction('failed'))
            ->sum('amount');
    }

    public function hasFailedRefunds()
    {
        return $this
            ->refunds
            ->whereIn('status', RefundStatus::withAction('failed'))
            ->sum('amount');
    }

    public function maxRefundAmount(): float|int
    {
        return ($this->amount / 100) - $this->refunded();
    }

    public function isRefundAvailable(): bool
    {
        return ($this->amount / 100) - $this->refunded() > 0;
    }

}
