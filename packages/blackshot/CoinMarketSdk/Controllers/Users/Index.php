<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;

use Blackshot\CoinMarketSdk\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Index extends \App\Http\Controllers\Controller
{

    const PAGINATE_PER_PAGE = 50;

    public function index(Request $request)
    {
        $filter = $request->has('filter')
            ? $request->input('filter')
            : [];

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('users.edit', Auth::id());
        }

        return view('blackshot::users.index', [
            'users' => self::getFilterUsers($filter),
            'users_without_tariff_count' => User::where('tariff_id', null)->count(),
            'filter' => $filter
        ]);
    }

    public static function getFilterUsers(array $filter)
    {
        if (!$filter) return User::with(['tariff'])
            ->paginate(self::PAGINATE_PER_PAGE);

        $users = User::with([]);

        if (key_exists('email', $filter) && !empty(trim($filter['email']))) {
            $users->where('email', 'like', '%'. $filter['email'] .'%');
        }

        if (key_exists('name', $filter) && !empty(trim($filter['name']))) {
            $users->where('name', 'like', '%'. $filter['name'] .'%');
        }

        if (key_exists('tariff_id', $filter) && !empty(trim($filter['name']))) {
            $users->where('tariff_id', $filter['tariff_id']);
        }

        return $users->with(['tariff'])->paginate(self::PAGINATE_PER_PAGE);
    }
}
