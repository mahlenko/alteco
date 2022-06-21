<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use App\Models\User;
use Blackshot\CoinMarketSdk\Repositories\UserRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SyncGetCources extends \App\Http\Controllers\Controller
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function webhook(Request $request): array
    {
        $validator = Validator::make($request->toArray(), [
            'user_email' => ['required', 'email'],
            'expired_at' => ['required', 'date'],
            'tariff_id' => ['nullable', Rule::exists('tariffs', 'id')]
        ]);

        if ($validator->fails()) {
            return [
                'ok' => false,
                'errors' => $validator->errors()
            ];
        }

        $data = $validator->validate();

        /* @var User $user */
        $user = UserRepository::findByEmail($data['user_email']);

        if ($user) {
            $user->setExpiredAt(new DateTimeImmutable($data['expired_at']));
            $user->save();
        } else {
            $user = UserRepository::create(
                'User ' . (User::all()->count() + 1),
                $data['user_email'],
                Str::random(8),
                $data['$validator'],
                User::ROLE_USER,
                new DateTimeImmutable($data['expired_at'])
            );
        }

        return [
            'ok' => true,
            'data' => $user
        ];
    }
}
