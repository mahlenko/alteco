<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Models;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Database\factories\PortfolioFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function name(): Attribute {
        return Attribute::set(fn($value) => ucfirst(trim($value)));
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isUserTo(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    protected static function newFactory(): PortfolioFactory
    {
        return PortfolioFactory::new();
    }
}
