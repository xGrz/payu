<?php

namespace xGrz\PayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use xGrz\PayU\Casts\Amount;
use xGrz\PayU\Enums\RefundStatus;

class Refund extends Model
{
    use SoftDeletes;

    protected $table = 'payu_refunds';

    protected $guarded = ['id'];
    protected $casts = [
        'status' => RefundStatus::class,
        'amount' => Amount::class
    ];

    protected $appends = [
        'errorDescription'
    ];


    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function getErrorDescriptionAttribute(): string
    {
        if (!$this->error) return '';

        $transKey = 'payu::refunds.errors.' . $this->error;
        return __($transKey) === $transKey
            ? str($this->error)->lower()->headline()->lower()->ucfirst()
            : __($transKey);

    }

}
