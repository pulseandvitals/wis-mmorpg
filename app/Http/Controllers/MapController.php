<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        return auth()->check()
            ? redirect(route('world.map',auth()->user()->player->current_map_id))
            : redirect(route('Welcome'));
    }
}
