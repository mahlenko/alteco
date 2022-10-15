<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionDeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Requests\TransactionDeleteRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\TransactionCreateRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\TransactionRequest;
use Blackshot\CoinMarketSdk\Portfolio\Resources\TransactionResource;
use Blackshot\CoinMarketSdk\Portfolio\Resources\PortfolioResource;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Транзакции по активу
     * @param TransactionRequest $request
     * @return JsonResponse
     * @throws TransactionException
     */
    public function transactions(TransactionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = Auth::user();

        $portfolio = Portfolio::find($data['portfolio_id']);
        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new TransactionException('Вы не можете просматривать транзакции этого портфолио.');
        }

        $transaction = $portfolio->assets()
            ->where('coin_uuid', $data['coin_uuid'])
            ->get();

        return $this->ok(data: [
            'coin' => Coin::find($data['coin_uuid']),
            'transactions' => TransactionResource::collection($transaction)
        ]);
    }

    /**
     * Страница добавления актива
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function add(Portfolio $portfolio): JsonResponse
    {
        $coins = CoinRepository::handle(with: [])
            ->pluck('name', 'uuid');

        return $this->ok(data: [
            'portfolio' => new PortfolioResource($portfolio),
            'coins' => $coins
        ]);
    }

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
     * @throws TransactionException
     */
    public function delete(TransactionDeleteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $portfolio = Portfolio::find($data['portfolio_id']);

        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new TransactionException('Вы не можете удалить этот актив.');
        }

        /* @var Transaction $transaction */
        $transaction = $portfolio->transactions()
            ->where('uuid', $data['uuid'])
            ->first();

        if (!$transaction) {
            throw new TransactionException('Актив не найден.', 404);
        }

        return TransactionDeleteAction::handle(Auth::user(), $transaction)
            ? $this->ok('Актив удален из портфолио.')
            : $this->fail('Не удалось удалить актив. Попробуйте позже.');
    }
}
