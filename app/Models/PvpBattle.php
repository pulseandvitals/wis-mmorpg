<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PvpBattle extends Model
{
    protected $fillable = [
        'challenger_id',
        'opponent_id',
        'turn_player_id',
        'status',
        'winner_id'
    ];
}
