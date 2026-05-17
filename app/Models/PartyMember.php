<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartyMember extends Model
{
    protected $fillable = [
        'party_room_id',
        'player_id',
        'joined_at'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
