<?php

namespace Blackshot\CoinMarketSdk\Models;

use DateTimeImmutable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

/**
 *
 */
class Coin extends Model
{
    /**
     * @var string
     */
    protected $table = 'coins';

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'rank', 'name', 'symbol', 'slug', 'is_active',
        'first_historical_data', 'last_historical_data'
    ];

    /**
     * @return HasOneThrough
     */
    public function platform(): HasOneThrough
    {
        return $this->hasOneThrough(
            Platform::class,
            CoinPlatformRelation::class,
            'coin_uuid', 'uuid',
            'uuid', 'platform_uuid'
        );
    }

    /**
     * @return HasOne
     */
    public function info(): HasOne
    {
        return $this->hasOne(CoinInfo::class, 'coin_uuid');
    }

    /**
     * @return HasMany
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class)
            ->orderBy('last_updated');
    }

    /**
     * @param string $currency
     * @return Quote
     */
    public function current(string $currency = 'USD'): Quote
    {
        return $this
            ->quotes
            ->where('currency', $currency)
            ->last() ?? new Quote();
    }

    /**
     * Список валют, на которые подписаны пользователи
     */
    public static function favorites(): Collection
    {
        $users_favorite = UserFavorites::all();
        if (!$users_favorite->count()) return collect();

        return Coin::find($users_favorite->pluck('coin_uuid'));
    }

    /**
     * @return HasManyThrough
     */
    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            CategoryModel::class,
            CoinCategory::class,
            'coin_uuid',
            'uuid',
            'uuid',
            'category_uuid',
        );
    }

    /**
     * @return mixed
     */
    public function foundsCategory()
    {
        return $this->categories
            ->where('type', CategoryModel::TYPE_FOUNDS);
    }

    public function otherCategory()
    {
        return $this->categories
            ->where('type', CategoryModel::TYPE_OTHER);
    }

    /**
     * @return float|int
     */
    public function getSmaAttribute()
    {
        // $quotes за определенный период
        // ...
        $quotes = $this->quotes;

        return $quotes->pluck('price')->sum() / $quotes->count();
    }

    /**
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable|null $to
     * @return mixed
     * @throws Exception
     */
    public function quotesByDate(DateTimeImmutable $from, DateTimeImmutable $to = null)
    {
        if (!$to) $to = new DateTimeImmutable($from->format('Y-m-d 23:59:59'));

        $from = $from->setTime(0, 0);
        $to = $to->setTime(23, 59, 59);

        return $this->quotes->whereBetween('last_updated', [
            $from->format('Y-m-d H:i:s'),
            $to->format('Y-m-d H:i:s')
        ])->sortBy('last_updated')->values();
    }

    /**
     * @param DateTimeImmutable $date
     * @return float
     * @throws Exception
     */
    public function open(DateTimeImmutable $date): float
    {
        $quotes = $this->quotesByDate($date);
        return $quotes->count() ? $quotes->first()->price : 0;
    }

    /**
     * @param DateTimeImmutable $date
     * @return float
     * @throws Exception
     */
    public function close(DateTimeImmutable $date): float
    {
        $quotes = $this->quotesByDate($date);
        return $quotes->count() ? $quotes->last()->price : 0;
    }

    /**
     * @param DateTimeImmutable $date
     * @return float
     * @throws Exception
     */
    public function high(DateTimeImmutable $date): float
    {
        $quotes = $this->quotesByDate($date);
        return $quotes->count() ? $quotes->max('price') : 0;
    }

    /**
     * @param DateTimeImmutable $date
     * @return float
     * @throws Exception
     */
    public function low(DateTimeImmutable $date): float
    {
        $quotes = $this->quotesByDate($date);
        return $quotes->count() ? $quotes->min('price') : 0;
    }

    /**
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable|null $end
     * @return int
     * @throws Exception
     */
    public function changeRankByPeriod(DateTimeImmutable $start, DateTimeImmutable $end = null): int
    {
        $quotes = $this->quotesByDate($start, $end);
        if (!$quotes->count()) return 0;

        $start = $quotes->first()->cmc_rank;
        $end = $quotes->last()->cmc_rank;

        return $end - $start;
    }

    public function getEmaAttribute()
    {
        return 10;
    }

    /**
     * @param Platform $platform
     * @return CoinPlatformRelation
     */
    public function attachPlatform(Platform $platform): CoinPlatformRelation
    {
        return CoinPlatformRelation::create([
            'coin_uuid' => $this->uuid,
            'platform_uuid' => $platform->uuid
        ]);
    }

    /**
     * @param Quote $quote
     * @return Quote
     * @throws Exception
     */
    public function attachQuote(Quote $quote): Quote
    {
        $quote->last_updated = $quote->last_updated instanceof DateTimeImmutable
            ? $quote->last_updated->format('Y-m-d H:i:s')
            : (new DateTimeImmutable($quote->last_updated))->format('Y-m-d H:i:s');

        $is_unique = !(bool) Quote::where([
            'coin_uuid' => $this->uuid,
            'currency' => $quote->currency,
            'last_updated' => $quote->last_updated
        ])->count();

        if (!$is_unique) return $quote;

        $quote->coin_uuid = $this->uuid;
        $quote->save();

        $this->rank = $quote->cmc_rank;
        $this->save();

        return $quote;
    }
}
