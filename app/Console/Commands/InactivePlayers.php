<?php

namespace App\Console\Commands;

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
    protected $description = 'Inactive players for 30 minutes will automatically shutdown and tagged as offline.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = Carbon::now()->subMinutes(30);

        $inactivePlayers = Player::where('updated_at', '<', $limit)
            ->where('is_online', true)
            ->get();

        foreach ($inactivePlayers as $player) {
            $player->is_online = false;
            $player->save();
        }

        $this->info("Marked {$inactivePlayers->count()} players as offline.");
    }
}
