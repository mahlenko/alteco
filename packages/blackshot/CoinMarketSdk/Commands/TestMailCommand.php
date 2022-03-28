<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Mail\TestMail;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:mailtest {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тест отправки письма';

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
        $to = $this->argument('email');

        Mail::to($to)->send(new TestMail());

        return Command::SUCCESS;
    }
}
