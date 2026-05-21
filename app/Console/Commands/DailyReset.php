<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily reset for all players.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
