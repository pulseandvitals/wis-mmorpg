<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PvpBattle extends Model
{
    protected $fillable = [
        'challenger_id',
        'opponent_id',
        'round',
        'round_processing',
        'challenger_events',
        'opponent_events',
        'status',
        'winner_id'
    ];
}
