<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Blackshot\CoinMarketSdk\Portfolio\Actions\CreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\DeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\UpdateAction;
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
        UpdateAction $update,
        CreateAction $create,
        Portfolio $portfolio = null): JsonResponse
    {
        $data = $request->validated();

        try {
            if ($portfolio) {
                $portfolio = $update::handle(Auth::user(), $portfolio, $data);
            } else {
                $portfolio = $create::handle(Auth::user(), $data['name']);
            }
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: $portfolio);
    }

    public function delete(DeleteRequest $request, DeleteAction $action): JsonResponse
    {
        $data = $request->validated();

        try {
            $action::handle(Auth::user(), Portfolio::find($data['id']));
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: $data['id']);
    }
}
