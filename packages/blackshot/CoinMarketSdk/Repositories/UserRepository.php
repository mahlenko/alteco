<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use App\Models\User;
use App\Notifications\UserRegistered;
use Blackshot\CoinMarketSdk\Models\Coin;
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

        /* @var User $user */
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => User::passwordHash($password)
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

        $user->fill([
            'name' => trim($name),
            'email' => $email
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
}
