<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers\api;

use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionDeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Requests\TransactionDeleteRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\TransactionCreateRequest;
use Blackshot\CoinMarketSdk\Portfolio\Resources\TransactionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiTransactionController extends Controller
{
    /**
     * Добавляем актив
     * @param TransactionCreateRequest $request
     * @return JsonResponse
     * @throws TransactionException
     */
    public function create(TransactionCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $transaction = TransactionCreateAction::handle(
                User::find($data['user_id']),
                Portfolio::find($data['portfolio_id']),
                Coin::find($data['coin_uuid']),
                $data);
        } catch (TransactionException $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: new TransactionResource($transaction));
    }

    /**
     * Обновим актив
     * @param TransactionCreateRequest $request
     * @return JsonResponse
     * @throws TransactionException
     */
    public function update(TransactionCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $asset = TransactionCreateAction::handle(
            User::find($data['user_id']),
            Portfolio::find($data['portfolio_id']),
            Coin::find($data['coin_uuid']),
            $data);

        return $this->ok(data: new TransactionResource($asset));
    }

    /**
     * Удалим актив
     * @throws TransactionException
     */
    public function delete(TransactionDeleteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $portfolio = Portfolio::find($data['portfolio_id']);

        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new TransactionException('Вы не можете удалить эту транзакцию.');
        }

        /* @var Transaction $transaction */
        $transaction = $portfolio->transactions()
            ->where('uuid', $data['uuid'])
            ->first();

        if (!$transaction) {
            throw new TransactionException('Транзакция не найдена.', 404);
        }

        return TransactionDeleteAction::handle(Auth::user(), $transaction)
            ? $this->ok('Транзакция удалена из портфолио.', $data)
            : $this->fail('Не удалось удалить транзакцию. Попробуйте позже.', $data);
    }
}
