<?php

namespace xGrz\PayU\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use xGrz\PayU\Enums\PaymentStatus;

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'payu_transactions';

    protected $casts = [
        'status' => PaymentStatus::class,
        'payload' => 'array'
    ];

    protected $guarded = [];

//    public function payMethod(): BelongsTo
//    {
//        return $this->belongsTo(PayMethod::class, 'method_id');
//    }
//
//    public function refunds(): HasMany
//    {
//        return $this->hasMany(PayURefund::class, 'transaction_id')->latest();
//    }
//
//    public function refunded(): int
//    {
//        return $this->refunds()->sum('payu_refunds.amount') / 100 ?? 0;
//    }

}
