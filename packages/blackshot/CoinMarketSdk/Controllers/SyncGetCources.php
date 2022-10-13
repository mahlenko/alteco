<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Models\User;
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
            'tariff_id' => ['nullable', Rule::exists('tariffs', 'id')]
        ]);

        if ($validator->fails()) {
            return [
                'ok' => false,
                'errors' => $validator->errors()
            ];
        }

        $data = $validator->validate();

        $tariff = $data['tariff_id']
            ? TariffModel::find($data['tariff_id'])
            : TariffModel::where(['default', true])->first();

        if (!$tariff) {
            return [
                'ok' => false,
                'description' => 'Не указан тариф пользователя.'
            ];
        }

        /* @var User $user */
        $user = UserRepository::findByEmail($data['user_email']);

        if ($user) {
            // добавляем дни от тарифа
            $expired_at = $user->expired_at->modify('+'. $tariff->days .' days');

//            $user->setExpiredAt($expired_at);
            $user->expired_at = $expired_at;
            $user->save();
        } else {
            $expired_at = new DateTimeImmutable('+' . $tariff->days .' days');

            $user = UserRepository::create(
                'User ' . (User::count() + 1),
                $data['user_email'],
                Str::random(8),
                $tariff->id,
                User::ROLE_USER,
                $expired_at
            );
        }

        return [
            'ok' => true,
            'data' => $user
        ];
    }
}
