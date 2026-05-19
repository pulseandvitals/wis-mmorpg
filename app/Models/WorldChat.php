<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldChat extends Model
{
    protected $fillable = [
        'player_id',
        'message',
        'channel'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
