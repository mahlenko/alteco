<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use App\Models\User;
use App\Notifications\UserRegistered;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\TariffModel;
use DateTimeImmutable;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;

class UserRepository
{
    /**
     * @param int $user_id Rnj
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     * @param DateTimeImmutable|null $expired_at
     * @return User
     */
    public static function create(
        string $name,
        string $email,
        string $password,
        string $tariff_id = null,
        string $role = User::ROLE_USER,
        DateTimeImmutable $expired_at = null
    ): User
    {
        if ((Auth::check() && !Auth::user()->isAdmin())) {
            throw new DomainException('Only the administrator can add new users');
        }

        self::isUniqueEmail($email);

        if (empty($password)) {
            throw new InvalidArgumentException('The password cannot be empty.');
        }

        $tariff_id = self::getTariffId($tariff_id);

        /* @var User $user */
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => User::passwordHash($password),
            'tariff_id' => $tariff_id,
        ]);

        $user->changeRole($role, Auth::id() ?? 1);

        //
        if ($expired_at) {
            $user->setExpiredAt($expired_at);
        } else {
            $user->unsetExpiredAt();
        }

        $user->save();

        //
        $user->notify(new UserRegistered($password));


        return $user;
    }

    /**
     * @param User $user
     * @param string $name
     * @param string $email
     * @param string|null $password
     * @param string $role
     * @param DateTimeImmutable|null $expired_at
     * @return User
     */
    public static function update(
        User $user,
        string $name,
        string $email,
        string $password = null,
        string $tariff_id = null,
        string $role = User::ROLE_USER,
        DateTimeImmutable $expired_at = null
    ): User
    {
        //
//        if (Auth::id() != $user->id && !Auth::user()->isAdmin()) {
//            throw new DomainException('You can\'t edit someone else\'s profile.');
//        }

        //
        self::isUniqueEmail($email, $user);

        //
        if (!empty($password)) $user->updatePassword($password);

        //
        if (!Auth::user()->isAdmin()) $role = User::ROLE_USER;

        $tariff_id = self::getTariffId($tariff_id);

        $user->fill([
            'name' => trim($name),
            'email' => $email,
            'tariff_id' => $tariff_id,
        ]);

        if (Auth::user()->isAdmin()) {
            $user->changeRole($role, Auth::id() ?? 1);
        }

        if ($expired_at && !is_null($expired_at)) {
            $user->setExpiredAt($expired_at);
        }

        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @param User|null $skip_user
     */
    public static function isUniqueEmail(string $email, User $skip_user = null)
    {
        if ($skip_user) {
            $unique_email = !(bool) User::where('id', '<>', $skip_user->id)
                ->where('email', $email)
                ->count();
        } else {
            $unique_email = !(bool) User::where('email', $email)->count();
        }

        if (!$unique_email) {
            throw new InvalidArgumentException(__('validation.unique', ['attribute' => 'email']));
        }
    }

    /**
     * @param User $user
     * @param Coin $coin
     * @return bool|null
     */
    public static function toggleBuyingCoin(User $user, Coin $coin): ?bool
    {
        if ($user->buyingCoins->where('uuid', $coin->uuid)->count()) {
            return $user->stopBuyingCoin($coin);
        }

        return $user->startBuyingCoin($coin);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public static function findByEmail(string $email): ?User
    {
        return User::where(['email' => $email])
            ->first();
    }

    /**
     * @param int|null $tariff_id
     * @return int
     */
    private static function getTariffId(int $tariff_id = null): int
    {
        if ($tariff_id > 0) return $tariff_id;

        $tariff = TariffModel::where('default', true)->first();
        return $tariff->id;
    }
}
