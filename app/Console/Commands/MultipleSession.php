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
        $userId = auth()->id();

        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->get();

        if ($sessions->count() > 1) {
            $sessionsToDelete = $sessions->slice(1);

            DB::table('sessions')
                ->whereIn('id', $sessionsToDelete->pluck('id'))
                ->delete();
        }

        $this->info('Multiple sessions cleaned successfully.');
    }
}
