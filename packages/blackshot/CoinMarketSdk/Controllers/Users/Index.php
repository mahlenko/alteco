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

        if (key_exists('email', $filter) && $filter['email']) {
            $users = User::where('email', 'like', '%'. $filter['email'] .'%')
                ->paginate(self::PAGINATE_PER_PAGE);
        } else {
            $users = User::paginate(self::PAGINATE_PER_PAGE);
        }

        return view('blackshot::users.index', [
            'users' => $users,
            'filter' => $filter
        ]);
    }
}
