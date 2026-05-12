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
}
