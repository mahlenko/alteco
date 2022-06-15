<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Illuminate\Console\Command;

class SetCategoryTypesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:category:type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Установит категориям типы';

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
     */
    public function handle()
    {
        //
        $updated_count_founds = CategoryModel::where('name', 'like', '% portfolio')
            ->update(['type' => CategoryModel::TYPE_FOUNDS]);

        $this->info('Set "'. CategoryModel::TYPE_FOUNDS .'" for ' . $updated_count_founds .' rows.');

        //
        $updated_count_other = CategoryModel::where('name', 'not like', '% portfolio')
            ->update(['type' => CategoryModel::TYPE_OTHER]);

        $this->info('Set "'. CategoryModel::TYPE_OTHER .'" for ' . $updated_count_other .' rows.');

        $this->info('DONE');

        return 0;
    }
}
