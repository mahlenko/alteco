<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Requests\UserDeleteRequest;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class Delete extends \App\Http\Controllers\Controller
{
    /**
     * @param UserDeleteRequest $request
     * @return RedirectResponse
     */
    public function index(UserDeleteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /* @var User $user */
        $user = User::findOrFail($data['id']);

        if ($user->id != Auth::id() && !Auth::user()->isAdmin()) {
            //
            flash('Insufficient rights to delete a user')->error();
            return back();
        }

        try {
            $user->remove();
        } catch (DomainException $exception) {
            flash($exception->getMessage())->error();
            return back();
        }

        return Auth::user()->isAdmin()
            ? back()
            : redirect()->route('home');
    }
}
