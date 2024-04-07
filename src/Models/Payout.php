<?php

namespace xGrz\PayU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use xGrz\PayU\Casts\Amount;
use xGrz\PayU\Enums\PayoutStatus;

/**
 * @method static findOrFail(int $payoutId)
 */
class Payout extends Model
{

    use SoftDeletes;

    protected $table = 'payu_payouts';

    protected $casts = [
        'status' => PayoutStatus::class,
        'amount' => Amount::class,
    ];

    protected $appends = [
        'errorDescription'
    ];


    protected $guarded = ['id', 'payoutId'];

    public function getErrorDescriptionAttribute(): string
    {
        if (!$this->error) return '';

        $transKey = 'payu::payouts.errors.' . $this->error;
        return __($transKey) === $transKey
            ? str($this->error)->lower()->headline()->lower()->ucfirst()
            : __($transKey);

    }
}
