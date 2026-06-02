<?php

namespace App\Console\Commands;

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InactivePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:inactive-players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inactive players for 60 minutes will automatically shutdown and tagged as offline.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $inactivePlayers = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '<', now()->subMinutes(80)->timestamp)
            ->pluck('user_id');

        /*
        |--------------------------------------------------------------------------
        | OFFLINE PLAYERS
        |--------------------------------------------------------------------------
        */
        DB::table('players')
            ->whereIn('id', $inactivePlayers)
            ->update([
                'is_online' => false
            ]);

        $this->info("Offline: {$inactivePlayers->count()}");
    }
}
