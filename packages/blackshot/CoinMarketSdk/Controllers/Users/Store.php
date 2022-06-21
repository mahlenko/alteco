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
     * @throws Exception
     */
    public function index(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (key_exists('expired_at', $data)) {
            $data['expired_at'] = new DateTimeImmutable($data['expired_at']);
        }

        try {
            if (key_exists('id', $data) && $data['id']) {
                UserRepository::update(
                    User::find($data['id']),
                    $data['name'],
                    $data['email'],
                    $data['password'],
                    $data['tariff_id'],
                    $data['role'] ?? User::ROLE_USER,
                    $data['expired_at'] ?? null
                );

            } else {
                UserRepository::create(
                    $data['name'],
                    $data['email'],
                    $data['password'],
                    $data['tariff_id'],
                    $data['role'] ?? User::ROLE_USER,
                    $data['expired_at'] ?? null
                );
            }

        } catch (Exception $exception) {
            flash($exception->getMessage())->error();
            return back()->withInput();
        }

        return redirect()->route('users.home');
    }
}
