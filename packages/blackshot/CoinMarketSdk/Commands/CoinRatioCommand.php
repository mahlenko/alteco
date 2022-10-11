<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Actions\Calculate\Coeficents\AlphaAction;
use Blackshot\CoinMarketSdk\Actions\Calculate\Coeficents\BetaAction;
use Blackshot\CoinMarketSdk\Actions\Calculate\Coeficents\SquidAction;
use Blackshot\CoinMarketSdk\Actions\Calculate\ProfitAction;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Repositories\QuoteRepository;
use Illuminate\Console\Command;

/**
 * Requires an PECL PHP Trader lib
 * install: pecl install trader
 */
class CoinRatioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:coin:ratio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рассчитает коэффициенты Beta, Alpha, Squid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info($this->description);

        $quotes = QuoteRepository::price();

        $tableResult = [];
        foreach (CoinRepository::handle() as $coin) {
            /*  */
            $prices = $quotes->get($coin->uuid);
            if (!$prices) continue;

            /*  */
            if (!$crix = QuoteRepository::crix($prices)) continue;
            $crixProfit = ProfitAction::handle($crix, 'index');

            /*  */
            if (!$beta = BetaAction::handle($coin, $crixProfit, $prices)) continue;
            $coin->beta = $beta[count($beta) - 1];

            /* */
            $coin->alpha = AlphaAction::handle(
                $prices->pluck('price')->toArray(),
                $crix->pluck('index')->toArray(),
                $coin->beta);

            // squid ...
            $coin->squid = SquidAction::handle($coin, $prices);

            $coin->save();

            $tableResult[] = [
                'name' => $coin->name,
                'beta' => $coin->beta,
                'alpha' => $coin->alpha,
                'squid' => $coin->squid
            ];
        }

        $this->table(['name', 'beta', 'alpha', 'squid'], $tableResult);

        return self::SUCCESS;
    }
}
