<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioDeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioUpdateAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Requests\PortfolioDeleteRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\PortfolioCreateRequest;
use Blackshot\CoinMarketSdk\Portfolio\Requests\PortfolioUpdateRequest;
use Blackshot\CoinMarketSdk\Portfolio\Resources\PortfolioResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioController extends Controller
{
    public function index(): JsonResource
    {
        return PortfolioResource::collection(Auth::user()->portfolios);
    }

    /**
     * Добавить портфолио
     * @param PortfolioCreateRequest $request
     * @param PortfolioCreateAction $action
     * @return JsonResponse
     */
    public function add(PortfolioCreateRequest $request, PortfolioCreateAction  $action): JsonResponse
    {
        $data = $request->validated();

        try {
            $portfolio = $action::handle(Auth::user(), $data);
        } catch (PortfolioException $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: new PortfolioResource($portfolio));
    }

    /**
     * Обновить портфолио
     * @param PortfolioUpdateRequest $request
     * @return JsonResponse
     */
    public function update(PortfolioUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $portfolio = Portfolio::find($data['portfolio_id']);

        try {
            $portfolio = PortfolioUpdateAction::handle(
                Auth::user(),
                $portfolio,
                $data);
        } catch (PortfolioException $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: new PortfolioResource($portfolio));
    }

    /**
     * Удаление портфолио
     * @param PortfolioDeleteRequest $request
     * @param PortfolioDeleteAction $action
     * @return JsonResponse
     */
    public function delete(PortfolioDeleteRequest $request, PortfolioDeleteAction $action): JsonResponse
    {
        $data = $request->validated();

        try {
            $action::handle(Auth::user(), $data['id']);
        } catch (PortfolioException $exception) {
            return $this->fail($exception->getMessage());
        }

        return $this->ok(data: [
            'id' => $data['id']
        ]);
    }
}
