<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public function saveMap($map)
    {
        $player = auth()->user()->player;
        if($player->current_map_id !== $map) {
            $player->current_map_id = $map;
            $player->save();
        }
    }

    public static function rumble(string $text): string
    {
        $chars = mb_str_split($text);
        shuffle($chars);
        return implode('', $chars);
    }

    public function multipleSession()
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
    }
}
