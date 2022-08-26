<?php

namespace Blackshot\CoinMarketSdk\Middleware;

use Closure;
use DateTimeImmutable;
use Illuminate\Http\Request;

class Subscribe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->checkExpiredAt(new DateTimeImmutable())) {
            abort(503, 'Продлите подписку, чтобы продолжить использовать Криптосканер.');
        }

        return $next($request);
    }
}
