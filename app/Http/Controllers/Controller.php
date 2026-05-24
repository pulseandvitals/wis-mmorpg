<?php

namespace App\Http\Controllers;

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
}
