<?php

namespace App\Http\Middleware;

use DateTimeImmutable;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * @throws AuthenticationException
     * @throws Exception
     */
    protected function authenticate($request, array $guards)
    {
        if (Auth::check() && !Auth::user()->checkExpiredAt(new DateTimeImmutable('now'))) {

            $expired = (new DateTimeImmutable(Auth::user()->expired_at ?? 'now'))
                ->format('d.m.Y H:i');

            flash('Ваша подписка истекла '. $expired .'.')->warning();

            Auth::logout();

            parent::unauthenticated($request, $guards);
        }

        parent::authenticate($request, $guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
//        if (! $request->expectsJson()) {
//            return route('login');
//        }
        dd($request);
    }
}
