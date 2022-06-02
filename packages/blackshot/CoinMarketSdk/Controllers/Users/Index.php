<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;

use App\Models\User;
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
            'filter' => $filter
        ]);
    }

    public static function getFilterUsers(array $filter)
    {
        if (!$filter) return User::paginate(self::PAGINATE_PER_PAGE);

        $users = User::with([]);

        if (key_exists('email', $filter) && !empty(trim($filter['email']))) {
            $users->where('email', 'like', '%'. $filter['email'] .'%');
        }

        if (key_exists('name', $filter) && !empty(trim($filter['name']))) {
            $users->where('name', 'like', '%'. $filter['name'] .'%');
        }

        return $users->paginate(self::PAGINATE_PER_PAGE);
    }
}
