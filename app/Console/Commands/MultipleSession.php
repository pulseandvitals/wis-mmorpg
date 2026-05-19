<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MultipleSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:multiple-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Player that has multiple session at once will logout.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all users with more than 1 session
        $users = DB::table('sessions')
            ->select('user_id')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($users as $user) {

            // Get all sessions of that user ordered by last activity
            $sessions = DB::table('sessions')
                ->where('user_id', $user->user_id)
                ->orderByDesc('updated_at')
                ->get();

            // Keep the latest session, delete the rest
            $keepSession = $sessions->shift();

            foreach ($sessions as $session) {
                DB::table('sessions')
                    ->where('id', $session->id)
                    ->delete();
            }
        }

        $this->info('Multiple sessions cleaned successfully.');
    }
}
