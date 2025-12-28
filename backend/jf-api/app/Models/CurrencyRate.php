<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'rate',
        'buy_rate',
        'sell_rate',
        'flag',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'buy_rate' => 'decimal:4',
        'sell_rate' => 'decimal:4',
    ];

    /**
     * Get all exchanges using this currency
     */
    public function exchanges(): HasMany
    {
        return $this->hasMany(CurrencyExchange::class, 'to_currency', 'code');
    }
}
