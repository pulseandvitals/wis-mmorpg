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

    public function members()
    {
        return $this->hasMany(GuildMember::class);
    }
}
