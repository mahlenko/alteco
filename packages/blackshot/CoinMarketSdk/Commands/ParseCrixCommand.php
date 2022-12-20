<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\CrixIndex;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use FastSimpleHTMLDom\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ParseCrixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:parse:crix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг индексов Crix';

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
     * @see https://www.swe-notes.ru/post/exp_smoothing/article
     * @return int
     */
    public function handle()
    {
        try {
            $crix_indexes = self::parseDataFromHtml();

            if (!$crix_indexes) {
                $this->error('Данные не получены. Возможно нужно проверить парсинг и подправить.');
            }

        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }

        if (isset($crix_indexes) && $crix_indexes) {

            $max_date = CrixIndex::max('date');

            foreach ($crix_indexes as $item) {
                if (!$max_date || $max_date < $item['date']) {
                    if (!CrixIndex::where('date', $item['date']->format('Y-m-d'))->count()) {
                        CrixIndex::create($item);
                        $this->info('Add ' . $item['date']->format('d.m.Y'));
                    }
                }
            }
        }

        return 0;
    }


    private static function parseDataFromHtml()
    {
        $html = Http::get('https://www.royalton-crix.com/')->body();

        $between_data = [
            'Highcharts.stockChart\(\'containerCrix\', {',
            '}\)'
        ];

        preg_match('|'. $between_data[0] .'(.+).'. $between_data[1] .'|isU', $html, $matches);
        if (!key_exists('1', $matches)) {
            return null;
        }

        return self::getData($matches[1]);
    }

    /**
     * Разбор данных из строки JS JSON
     * @param string $string
     * @return array|null
     */
    private static function getData(string $string)
    {
        if (empty($string)) return null;

        $string_rows = explode("\n", $string);
        $data = null;

        // поиск строки с данными
        foreach ($string_rows as $index => $row) {
            $clear_row = trim($row);
            if (empty($clear_row)) continue;

            // поиск строки с данными
            if (Str::contains($clear_row, 'data:')) {
                // в html, строка с данными перенесена на следующую строку
                $data = trim(rtrim($string_rows[$index + 1], ','));
                break;
            }
        }

        $result = [];
        if ($data) {
            $data = trim(rtrim(
                Str::replace('],[', ']'."\n".'[', $data),
                ']'
            ));

            foreach (explode("\n", $data) as $row) {
                // чистим "массив" от скобок
                $row = Str::replace(['[', ']'], '', $row);

                //
                list($timestamp, $index) = explode(',', $row);
                $result[] = [
                    'date' => (new \DateTimeImmutable())->setTimestamp(Str::substr($timestamp, 0, 10)),
                    'index' => floatval($index)
                ];
            }
        }

        return $result;
    }
}
