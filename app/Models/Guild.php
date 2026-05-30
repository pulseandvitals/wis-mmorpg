<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    protected $fillable = [
        'name',
        'leader_id',
        'level',
        'gold_stash',
        'icon'
    ];

    const GUILD_CREATION_COST = 500000;
    const GUILD_CREATION_COST_DIAMOND = 599;
    const MAX_MEMBER = 5;
    const COST_FOR_MULTIPLE_FILE_UPLOAD = 299;
    public function members()
    {
        return $this->hasMany(GuildMember::class);
    }

    public function leader()
    {
        return $this->belongsTo(Player::class);
    }
}
