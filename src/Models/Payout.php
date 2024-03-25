<?php

namespace xGrz\PayU\Models;

use Illuminate\Database\Eloquent\Model;
use xGrz\PayU\Casts\Amount;
use xGrz\PayU\Enums\PayoutStatus;

class Payout extends Model
{
    protected $table = 'payu_payouts';

    protected $casts = [
        'status' => PayoutStatus::class,
        'amount' => Amount::class,
    ];

    protected $guarded = ['id', 'payoutId'];
}
