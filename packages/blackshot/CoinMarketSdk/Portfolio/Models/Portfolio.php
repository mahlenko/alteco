<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Models;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Database\factories\PortfolioFactory;
use Blackshot\CoinMarketSdk\Portfolio\Entities\PortfolioItem;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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

    public function stacking(): HasMany
    {
        return $this->hasMany(Stacking::class);
    }

    /**
     * @return \Blackshot\CoinMarketSdk\Portfolio\Entities\Portfolio
     */
    public function items(): \Blackshot\CoinMarketSdk\Portfolio\Entities\Portfolio
    {
        $portfolio = new \Blackshot\CoinMarketSdk\Portfolio\Entities\Portfolio();

        $transaction = $this->transactions->load(['coin', 'coin.info'])
            ->groupBy('coin_uuid');

        $staking = $this->stacking->groupBy('coin_uuid');

        $transaction->map(function($group, $coin_uuid) use ($portfolio, $staking) {
            $portfolio->push(
                new PortfolioItem(
                    $this->getKey(),
                    $group->first()->coin,
                    $group,
                    $staking->get($coin_uuid)
                )
            );
        });

        return $portfolio;
    }

    public function total(): Attribute
    {
        return Attribute::get(fn() => $this->items()->total());
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
