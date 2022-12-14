<?php

namespace Blackshot\CoinMarketSdk\Models;

use App;
use Blackshot\CoinMarketSdk\Database\Factories\CoinFactory;
use Blackshot\CoinMarketSdk\Helpers\NumberHelper;
use Blackshot\CoinMarketSdk\Portfolio\Enums\PeriodEnum;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

/**
 *
 */
class Coin extends Model
{
    use HasFactory;

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
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'rank', 'name', 'symbol', 'slug', 'is_active',
        'first_historical_data', 'last_historical_data', 'alteco_desc',
    ];

    /**
     * @var string|null
     */
    public ?string $cache_quotes_key = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->cache_quotes_key = 'quotes:'. $this->uuid;
    }

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

    public function signals(): HasMany {
        return $this->hasMany(Signal::class);
    }

    /**
     * @param string $currency
     * @return Builder|Model|object|null
     */
    public function current(string $currency = 'USD')
    {
        $quotes = Cache::remember('quotes:current:' . $this->uuid, time() + (60 * 5), function() {
            return $this->quotes;
        });

        return $quotes
            ->where('currency', $currency)
            ->last() ?? new Quote();
    }

    /**
     * ???????????? ??????????, ???? ?????????????? ?????????????????? ????????????????????????
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
        // $quotes ???? ???????????????????????? ????????????
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

    /**
     * @param PeriodEnum $period
     * @return array
     * @throws Exception
     */
    public function profit(PeriodEnum $period): array
    {
        $now = new DateTimeImmutable();

        $start_date = match ($period) {
            PeriodEnum::hours24 => $now->modify('-1 day'),
            PeriodEnum::days7 => $now->modify('-7 days'),
            PeriodEnum::days30 => $now->modify('-30 days'),
            PeriodEnum::days90 => $now->modify('-90 days'),
            default => $now->modify('-1 year'),
        };

        $current = $this->price;
        $start_price = $this->quotesByDate($start_date, $start_date)
            ->last()?->price ?? $this->price;

        return [
            'price' => NumberHelper::format($current - $start_price),
            'percent' => NumberHelper::format((($current / $start_price) * 100) - 100)
        ];
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
     * @param array $data
     * @return Quote
     */
    public function attachQuote(array $data): Quote
    {
        if (!$data['cmc_rank']) {
            throw new InvalidArgumentException('?????? ???????????? ???? ??????????.', 604);
        }

        /*  */
        $quote = $this->quotes()
            ->where('last_updated', new DateTimeImmutable($data['last_updated']))
            ->first();

        if ($quote) return $quote;

        $quote = $this->quotes()->create($data);

        /*  */
        $this->rank = $data['cmc_rank'];
        $this->price = $data['price'];
        $this->percent_change_1h = $data['percent_change_1h'];

        $this->save();

        /*  */
        $this->forgetCache();

        return $quote;
    }

    public function forgetCache(): void
    {
        if (Cache::has($this->cache_quotes_key)) {
            Cache::forget($this->cache_quotes_key);
        }

        if (Cache::has('coins')) {
            Cache::forget('coins');
        }

        if (Cache::has('signals:'. $this->uuid)) {
            Cache::forget('signals:'. $this->uuid);
        }

        if (Cache::has('price:'. $this->uuid)) {
            Cache::forget('price:'. $this->uuid);
        }

        if (Cache::has('quotes:current:'. $this->uuid)) {
            Cache::forget('quotes:current:'. $this->uuid);
        }
    }

    public function getAlphaStatusAttribute()
    {
        $value = $this->alpha;

        return match (true) {
            $value < 0 => 'negative',
            $value >= 5 && $value <= 9.99 => 'good',
            $value >= 10 && $value <= 19.99 => 'nice',
            $value >= 20 => 'very-good',
            default => 'danger'
        };
    }

    public function getAlphaProgressPercentAttribute()
    {
        return $this->alpha
            ? min(($this->alpha / 20) * 100, 100)
            : 0;
    }

    public function getSquidStatusAttribute()
    {
        $value = $this->squid;

        return match (true) {
            $value < 0 => 'negative',
            $value >= 0.44 && $value <= 0.99 => 'good',
            $value >= 1.00 && $value <= 2.99 => 'nice',
            $value >= 3 => 'very-good',
            default => 'danger'
        };
    }

    public function getSquidProgressPercentAttribute()
    {
        return $this->squid
            ? min(($this->squid / 3) * 100, 100)
            : 0;
    }

    protected static function newFactory(): CoinFactory
    {
        return CoinFactory::new();
    }

    protected static function booted()
    {
        parent::booted();

        parent::creating(function(self $coin) {
            if (!$coin->uuid) {
                $coin->uuid = Uuid::uuid4();
            }
        });
    }
}
