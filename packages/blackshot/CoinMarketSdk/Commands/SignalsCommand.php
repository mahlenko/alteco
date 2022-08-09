<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\Quote;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SignalsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:signals {--date=now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сбор сигналов';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $date = new DateTimeImmutable($this->option('date') ?? Signal::max('date'));
        $quotes = Quote::select(['coin_uuid', 'cmc_rank'])
            ->whereBetween('last_updated', [
                $date->format('Y-m-d 00:00:00'),
                $date->format('Y-m-d 23:59:59')
            ])->get();

        $signals_latest = self::signalsByDate($date);

        $previous_date = new DateTimeImmutable(self::previousDateSignal($date));
        $signals_previous = self::signalsByDate($previous_date);

        $this->info('Start date: ' . $previous_date->format('Y-m-d'));
        $this->info('End date: ' . $date->format('Y-m-d'));

//        $quotes = DB::table('coin_quotes')
//            ->distinct()
//            ->select('coin_uuid')
////            ->selectRaw('FIRST_VALUE(`cmc_rank`) OVER (PARTITION BY coin_uuid ORDER BY last_updated ASC) start_day_rank')
//            ->selectRaw('FIRST_VALUE(`cmc_rank`) OVER (PARTITION BY coin_uuid ORDER BY last_updated DESC) end_day_rank')
//            ->whereBetween('last_updated', [
//                $date->format('Y-m-d 00:00:00'),
//                $date->format('Y-m-d 23:59:59')
//            ])->get();

//        dd($quotes->count());

        $this->info('Previous signals: ' . $signals_previous->count());
        $this->info('Quotes: ' . $quotes->count());

        $signals = self::createSignals($quotes, $signals_latest, $signals_previous);

        self::table(['coin', 'rank', 'previous rank', 'diff', 'date'], $signals);

        return 0;

//        foreach ($quotes as $quote) {
//            //
//            if (Cache::has('signals:'. $quote->coin_uuid)) {
//                Cache::forget('signals:'. $quote->coin_uuid);
//            }
//
//            //
//            try {
//                $signal = $signals_latest
//                    ->where('coin_uuid', $quote->coin_uuid)
//                    ->first();
//
//                $latest = $signals_previous
//                    ->where('coin_uuid', $quote->coin_uuid)
//                    ->first();
//
//                if (!$signal) {
//                    $signal = new Signal();
//                    $signal->coin_uuid = $quote->coin_uuid;
//                    $signal->date = $date;
//                }
//
//                $signal->rank = $quote->end_day_rank;
//                $signal->diff = $latest
//                    ? $latest->rank - $quote->end_day_rank
//                    : 0;
//
//                $signal->save();
//
//            } catch (Exception $exception) {
//                $this->warn($quote->coin_uuid .': '. $exception->getMessage());
//            }
//        }

        // Расчет экспоненциального ранка
//        $this->call('blackshot:rank:exponential');

        return self::SUCCESS;
    }

    /**
     * @param DateTimeImmutable $date
     * @return string
     */
    private static function previousDateSignal(DateTimeImmutable $date): string
    {
        return Signal::where('date', '<', $date->format('Y-m-d 00:00:00'))
            ->max('date');
    }

    /**
     * @param DateTimeImmutable $date
     * @return Collection
     */
    private static function signalsByDate(DateTimeImmutable $date): Collection
    {
        return Signal::whereBetween('date', [
            $date->format('Y-m-d 00:00:00'),
            $date->format('Y-m-d 23:59:59'),
        ])->get();
    }

    /**
     * @param Collection $quotes
     * @param Collection $latest
     * @param Collection $previous
     * @return Collection
     */
    private static function createSignals(
        Collection $quotes,
        Collection $latest,
        Collection $previous
    ): Collection {
        $collection = collect();

        /* @var Quote $quote */
        foreach ($quotes as $quote) {
            /* @var Signal $signal */
            $signal = $latest->where('coin_uuid', $quote->coin_uuid)->first();

            /* @var Signal $previous_signal */
            $previous_signal = $previous->where('coin_uuid', $quote->coin_uuid)->first();

            if (!$signal) {
                $signal = new Signal();
                $signal->coin_uuid = $quote->coin_uuid;
            }

            $signal->rank = $quote->cmc_rank;
            $signal->diff = self::calculateDiff($quote, $previous_signal);
            $signal->date = (new DateTimeImmutable($quote->last_updated))->format('Y-m-d');

            try {
                $signal->save();

                if (Cache::has('signals:' . $quote->coin_uuid)) {
                    Cache::forget('signals:'. $quote->coin_uuid);
                }
            } catch (Exception) {}

            $collection->push([
                'coin_uuid' => $signal->coin_uuid,
                'rank' => $signal->rank,
                'previous_rank' => $previous_signal->rank ?? '---',
                'diff' => $signal->diff,
                'date' => $signal->date->format('d.m.Y')
            ]);
        }

        return $collection;
    }

    /**
     * {previous} - {current} = {diff}
     * --------------------------------------------------
     * Examples:
     *  5 - 7 = -2  // упал на 2 позиции
     *  8 - 3 = 5   // поднялся на 5 позиций
     *
     * @param Quote $quote
     * @param Signal $previous_signal
     * @return void
     */
    private static function calculateDiff(Quote $quote, Signal $previous_signal = null): int
    {
        return $previous_signal
            ? $previous_signal->rank - $quote->cmc_rank
            : 0;
    }

}
