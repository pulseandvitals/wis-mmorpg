<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\WorldChat;
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
        Player::query()->update([
            'daily_bet_chance' => 10,
            'daily_trivia_chance' => 10,
            'daily_mobs_kill' => 100,
            'daily_fishing_chance' => 10
        ]);

        WorldChat::create([
            'message' => '🔄 Daily Reset has been completed! All daily chances and activities have been refreshed. A new day of adventure begins!',
            'channel' => 'world',
            'player_id' => null,
            'is_admin' => true,
        ]);
    }
}
