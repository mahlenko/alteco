<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\DeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\StoreAction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Requests\DeleteRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\StoreRequest;
use Blackshot\CoinMarketSdk\Portfolio\Resources\PortfolioCollection;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    public function index(): PortfolioCollection
    {
        return new PortfolioCollection(Auth::user()->portfolios);
    }

    public function store(
        StoreRequest $request,
        StoreAction $action,
        Portfolio $portfolio = null): JsonResponse
    {
        $data = $request->validated();

        try {
            $portfolio = $action::handle(Auth::user(), $data['name'], $portfolio);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: $portfolio);
    }

    public function delete(DeleteRequest $request, DeleteAction $action): JsonResponse
    {
        $data = $request->validated();

        try {
            $action::handle(Auth::user(), $data['id']);
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: $data['id']);
    }
}
