<?php

namespace xGrz\PayU\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;
use xGrz\PayU\Casts\Amount;

/**
 * @method static active()
 */
class Method extends Model
{
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $table = 'payu_methods';
    protected $guarded = ['code'];
    protected $casts = [
        'min_amount' => Amount::class,
        'max_amount' => Amount::class
    ];

    public function scopeActive(Builder $query, bool $isActive = true): void
    {
        $query->where('available', true)->where('active', $isActive);
    }

    public function scopeAmount(Builder $query, int|float $amount): void
    {
        $amountInCents = $amount * 100;

        $query
            ->where('min_amount', '<', $amountInCents)
            ->where('max_amount', '>', $amountInCents);
    }

    public function getMethod(): array
    {
        $this->makeHidden(['created_at', 'updated_at']);
        return $this->toArray();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'method_id');
    }

    public function getMinAttribute(): string
    {
        return Number::currency($this->min_amount, 'PLN', app()->getLocale());
    }

    public function getMaxAttribute(): string
    {
        return Number::currency($this->max_amount, 'PLN', app()->getLocale());
    }

}
