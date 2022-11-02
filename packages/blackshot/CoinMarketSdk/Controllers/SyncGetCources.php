<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Repositories\UserRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function webhook(Request $request)
    {
        Log::debug('Webhook request', $request->all());

        $validator = Validator::make($request->all(), [
            'user_email' => ['required', 'email'],
            'tariff_id' => ['nullable', Rule::exists('tariffs', 'id')]
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            Log::debug('Validation fails.', $errors);
            return $this->fail('Validation fails.', $errors);
        }

        $data = $validator->getData();

        /* Тариф */
        $tariff = key_exists('tariff_id', $data) && $data['tariff_id']
            ? TariffModel::find($data['tariff_id'])
            : TariffModel::where(['default' => true])->first();


        /* @var User $user */
        $user = UserRepository::findByEmail($data['user_email']);

        if ($user) {
            $now = new DateTimeImmutable();

            $start_date = $user->expiredDays()
                ? new DateTimeImmutable($user->expired_at)
                : $now;

            $user->setExpiredAt($start_date->modify(
                sprintf('+ %d days', $tariff->days)
            ));

            $user->save();

            Log::info(
                sprintf(
                    "Обновлена подписка пользователю %d, предыдущая подписка: тариф \"%s\" до %s, изменена на \"%s\" до %s",
                    $user->getKey(),
                    $user->tariff->name,
                    $start_date->format('j F Y'),
                    $tariff->name,
                    $user->expired_at->format('j F Y')
                )
            );
        } else {
            /* Новый пользователь */
            $user = UserRepository::create(
                'User ' . (User::count() + 1),
                $data['user_email'],
                Str::random(8),
                $tariff->id,
                User::ROLE_USER,
                (new DateTimeImmutable())->modify(sprintf('+ %d days', $tariff->days))
            );

            Log::info(
                sprintf(
                    "Зарегистрирован новый пользователь ID %d. Текущий тариф \"%s\" до %s.",
                    $user->getKey(),
                    $tariff->name,
                    $user->expired_at->format('j F Y')
                )
            );
        }

        return $this->ok('Webhook success', $user);
    }
}
