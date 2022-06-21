<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;

use App\Models\User;
use Blackshot\CoinMarketSdk\Models\TariffModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Edit extends \App\Http\Controllers\Controller
{
    /**
     * @param int|null $id
     * @return Application|Factory|View
     */
    public function index(int $id = null)
    {
        if (!Auth::user()->isAdmin() && !$id) {
            return redirect()->route('users.edit', Auth::id());
        }

        return view('blackshot::users.edit', [
            'user' => $id ? User::findOrFail($id) : null,
            'tariffs' => TariffModel::all()
        ]);
    }
}
