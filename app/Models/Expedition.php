<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    protected $fillable = [
        'player_id',
        'map_name',
        'hours',
        'exp_reward',
        'gold_reward',
        'drops',
        'ends_at',
        'status'
    ];
}
