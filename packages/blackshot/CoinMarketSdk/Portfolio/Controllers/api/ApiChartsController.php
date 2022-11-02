<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers\api;

use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Helpers\NumberHelper;
use Blackshot\CoinMarketSdk\Portfolio\Enums\PeriodEnum;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ApiChartsController extends Controller
{
    public function portfolio(Request $request): JsonResponse
    {
        $data = $request->validate([
            'portfolio_id' => ['required', 'integer', 'min:1'],
            'period' => ['required']
        ]);

        if (!$period = PeriodEnum::byName($data['period'])) {
            return response()->json();
        }

        $user = Auth::user();

        /* @var Portfolio $portfolio */
        $portfolio = $user->portfolios
            ->where('id', $data['portfolio_id'])
            ->firstOrFail();

        $options = [];
        $groupByDay = in_array($period, [PeriodEnum::days30, PeriodEnum::days90, PeriodEnum::all]);

        try {
            $chart_data = $portfolio
                ->items()->chartData($period, $groupByDay);
        } catch (PortfolioException $exception) {
            return $this->fail($exception->getMessage());
        }

        if ($chart_data->count()) {
            $options['colors'] = [
                $chart_data->last()['value'] - $chart_data->first()['value'] > 0
                    ? '#39A657'
                    : '#EE463D'
            ];

            foreach ($chart_data as $item) {
                $options['series'][0]['data'][] = floatval(str_replace(' ', '', NumberHelper::format($item['value'])));
                $options['xaxis']['categories'][] = $groupByDay
                    ? Carbon::make($item['last_updated'])->isoFormat('D MMMM g', 'Do MMMM')
                    : Carbon::make($item['last_updated'])->isoFormat('D MMMM g [в] HH:mm', 'Do MMMM');
            }
        }

        return $this->ok(data: $options);
    }
}
