<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    protected $fillable = [
        'guild_id',
        'player_id',
        'rank',
        'gold_contribution',
        'joined_at'
    ];
}
