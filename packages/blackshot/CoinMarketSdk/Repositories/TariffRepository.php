<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use App\Models\User;
use Blackshot\CoinMarketSdk\Models\TariffModel;
use DomainException;
use Illuminate\Support\Facades\DB;

/**
 * Тарифы
 */
class TariffRepository
{
    /**
     * Создание тарифа
     * @param string $name
     * @param float $amount
     * @param int $days
     * @param bool $free
     * @param bool $default
     * @param int $move_users
     * @return TariffModel
     */
    public static function create(
        string $name,
        float $amount = 0,
        int $days = 0,
        bool $free = false,
        bool $default = false,
        int $move_users = -1
    ): TariffModel
    {
        if (TariffModel::where('name', trim($name))->count()) {
            throw new DomainException('Тариф с таким названием уже есть.');
        }

        self::resetDefault($default);

        $tariffModel = TariffModel::create([
            'name' => trim($name),
            'amount' => $amount,
            'days' => $days,
            'free' => $free,
            'default' => $default
        ]);

        self::moveUsers($tariffModel->id, $move_users);

        return $tariffModel;
    }

    /**
     * Обновление тарифа
     * @param TariffModel $tariffModel
     * @param string $name
     * @param float|null $amount
     * @param int|null $days
     * @param bool $free
     * @param bool $default
     * @param int $move_users
     * @return TariffModel
     */
    public static function update(
        TariffModel $tariffModel,
        string $name,
        float $amount = null,
        int $days = null,
        bool $free = false,
        bool $default = false,
        int $move_users = -1
    ): TariffModel
    {
        $double = TariffModel::where('name', trim($name))
            ->where('id', '<>', $tariffModel->id);

        if ($double->count()) {
            throw new DomainException('Тариф с таким названием уже есть.');
        }

        if (!$tariffModel->default) {
            self::resetDefault($default);
        }

        self::moveUsers($tariffModel->id, $move_users);

        $tariffModel->fill([
            'name' => trim($name),
            'amount' => $amount,
            'days' => $days,
            'free' => $free,
            'default' => $default
        ])->save();

        return $tariffModel;
    }

    /**
     * @param bool $default
     * @return void
     */
    private static function resetDefault(bool $default): void
    {
        if (!$default) return;

        DB::table('tariffs')->update([
            'default' => false
        ]);
    }

    /**
     * Перенос пользователей с одного тарифа на другой
     * @todo $last_tariff_id пока не используется, задел на будущее
     *
     * @param int $tariff_id
     * @param int $last_tariff_id
     * @return int
     */
    public static function moveUsers(int $tariff_id, int $last_tariff_id = -1): int
    {
        if ($last_tariff_id < 0) return 0;

        if ($last_tariff_id > 0) {
            // перенесет пользователей из указанного тарифа
            return DB::table('users')
                ->where('tariff_id', $last_tariff_id)
                ->update([
                    'tariff_id' => $tariff_id
                ]);
        } elseif (is_null($last_tariff_id) || $last_tariff_id === 0) {
            // пользователи без тарифа
            return DB::table('users')
                ->where('tariff_id', null)
                ->update([
                    'tariff_id' => $tariff_id
                ]);
        }

        // перенесет всех пользователей
        return DB::table('users')->update([
            'tariff_id' => $tariff_id
        ]);
    }

}
