<?php

namespace Blackshot\CoinMarketSdk\Models;

use Blackshot\CoinMarketSdk\Database\Factories\UserFactory;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use DateTimeImmutable;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * User Administrator
     */
    const ROLE_ADMIN = 'admin';

    /**
     * User
     */
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tariff_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    /**
     * @return HasManyThrough
     */
    public function favorites(): HasManyThrough
    {
        return $this->hasManyThrough(
            Coin::class,
            UserFavorites::class,
            'user_id',
            'uuid',
            'id',
            'coin_uuid'
        );
    }

    public function favoritesUuids(): HasMany
    {
        return $this->hasMany(UserFavorites::class);
    }

    /**
     * @return HasManyThrough
     */
    public function trackings(): HasManyThrough
    {
        return $this->hasManyThrough(
            Coin::class,
            TrackingCoin::class,
            'user_id',
            'uuid',
            'id',
            'coin_uuid'
        );
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * @param string $role
     * @param int $user_id ???????????????????????? ???? ???????? ???????????????????? ???????????????????? ????????
     * @return User
     */
    public function changeRole(string $role, int $user_id): User
    {
        $this->protectedActionUpdate($user_id);

        if (!in_array($role, [self::ROLE_ADMIN, self::ROLE_USER])) {
            throw new DomainException('The selected role is not supported.');
        }

        $this->role = $role;
        return $this;
    }

    /**
     * @param Coin $coin
     * @return bool
     */
    public function startBuyingCoin(Coin $coin): bool
    {
        $buy = new UserCoinBuying([
            'user_id' => $this->id,
            'coin_uuid' => $coin->uuid
        ]);

        return $buy->save();
    }

    /**
     * @param Coin $coin
     * @return bool|null
     */
    public function stopBuyingCoin(Coin $coin): ?bool
    {
        /* @var UserCoinBuying $buying */
        $buying = UserCoinBuying::where([
            'user_id' => $this->id,
            'coin_uuid' => $coin->uuid
        ])->first();

        return $buying->delete();
    }

    /**
     * @return HasManyThrough
     */
    public function buyingCoins(): HasManyThrough
    {
        return $this->hasManyThrough(
            Coin::class,
            UserCoinBuying::class,
            'user_id',
            'uuid',
            'id',
            'coin_uuid',
        );
    }

    /**
     * @param string $password
     */
    public function updatePassword(string $password)
    {
        $this->password = self::passwordHash($password);
    }

    /**
     * @param string $password
     * @return string
     */
    public static function passwordHash(string $password):string
    {
        return Hash::make($password);
    }

    /**
     * @return bool|null
     */
    public function remove(): ?bool
    {
        $this->protectedActionUpdate(Auth::id());

        if (Auth::id() == $this->id) {
            throw new DomainException('You can\'t delete yourself.');
        }

        return $this->delete();
    }

    /**
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class);
    }

    /**
     * @return void
     */
    public function setExpiredAt(DateTimeImmutable $expire_at)
    {
        $this->expired_at = $expire_at->format('Y-m-d H:i:s');
    }

    public function unsetExpiredAt()
    {
        $this->expired_at = null;
    }

    public function isSubscribe()
    {
        return !$this->checkExpiredAt(new DateTimeImmutable());
    }

    /**
     * @param DateTimeImmutable $date
     * @return bool
     * @throws \Exception
     */
    public function checkExpiredAt(DateTimeImmutable $date): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        if (!$this->expired_at || !$this->tariff) {
            return true;
        }

        $expired_at = new DateTimeImmutable($this->expired_at);
        return $date->format('Y-m-d') > $expired_at->format('Y-m-d');
    }

    /**
     * @return int
     */
    public function expiredDays(): int
    {
        $days = $this->expired_at
            ? (new DateTimeImmutable())->diff($this->expired_at)->days
            : 0;

        return $days < 0 ? 0 : $days;
    }

    /**
     * @return string
     */
    public function expiredAtText(): string
    {
        $days = $this->expiredDays();

        if (!$this->isAdmin() && $days == 0) {
            return '???????????? ????????????';
        }

        return $this->isAdmin()
            ? '???????????? ????????????????????????????'
            : $days .' '. trans_choice('????????|??????|????????', $days);
    }

    /**
     * ?????????? ????????????????????????
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(TariffModel::class)->withDefault(function() {
            return TariffModel::where('default', true)
                ->first();
        });
    }

    /**
     * ?????????????? ????????????????????????
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(UserPaymentsModel::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class, 'user_id', 'id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * @throws DomainException
     */
    protected function protectedActionUpdate(int $user_id)
    {
        /* @var User $auth_user */
        $auth_user = User::findOrFail($user_id);

        if ($user_id == $this->id && !$auth_user->isAdmin()) {
            throw new DomainException('The data you want to change is protected from self-modification.');
        }

        if (!$auth_user->isAdmin()) {
            throw new DomainException('The selected role is not supported.');
        }
    }
}
