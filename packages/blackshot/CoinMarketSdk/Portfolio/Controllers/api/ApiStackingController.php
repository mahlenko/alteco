<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers\api;

use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Stacking\StakingCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Stacking;
use Blackshot\CoinMarketSdk\Portfolio\Requests\StackingCreateRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\StackingDeleteRequest;
use Blackshot\CoinMarketSdk\Portfolio\Resources\StackingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiStackingController extends Controller
{
    /**
     * Добавляем стейкинг монеты
     * @param StackingCreateRequest $request
     * @return JsonResponse
     */
    public function create(StackingCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $stacking = StakingCreateAction::handle(
                Auth::user(),
                Portfolio::find($data['portfolio_id']),
                Coin::find($data['coin_uuid']),
                $data
            );
        } catch (PortfolioException $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok('Монета добавлена в стейкинг.', new StackingResource($stacking));
    }

    /**
     * Удалим стейкинг
     * @todo Сделать через action
     */
    public function delete(StackingDeleteRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();

        $portfolio = $user->portfolios()->where('id', $data['portfolio_id'])->first();

        if ($portfolio->user_id != $data['user_id'] && !$user->isAdmin()) {
            return $this->fail('Вы не можете удалить эту запись.');
        }

        if (Stacking::find($data['id'])->delete()) {
            return $this->ok('Запись удалена', $data);
        }

        return $this->fail('Не удалось удалить запись.');
    }
}
