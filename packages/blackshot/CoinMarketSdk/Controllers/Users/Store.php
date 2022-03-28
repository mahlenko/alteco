<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;

use App\Models\User;
use Blackshot\CoinMarketSdk\Repositories\UserRepository;
use Blackshot\CoinMarketSdk\Requests\UserRequest;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\RedirectResponse;

class Store extends \App\Http\Controllers\Controller
{
    /**
     * @param UserRequest $request
     * @return RedirectResponse
     */
    public function index(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (key_exists('expired_at', $data)) {
            $data['expired_at'] = new DateTimeImmutable($data['expired_at']);
        }

        try {
            key_exists('id', $data)
                ? UserRepository::update(User::find($data['id']), $data['name'], $data['email'], $data['password'], $data['role'] ?? User::ROLE_USER, $data['expired_at'] ?? null)
                : UserRepository::create($data['name'], $data['email'], $data['password'], $data['role'] ?? User::ROLE_USER, $data['expired_at'] ?? null);
        } catch (Exception $exception) {
            flash($exception->getMessage())->error();
            return back()->withInput();
        }

        return redirect()->route('users.home');
    }
}
