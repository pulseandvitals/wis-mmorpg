<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartyRoom extends Model
{
    protected $fillable = [
        'code'
    ];

    public function members()
    {
        return $this->hasMany(PartyMember::class, 'party_room_id');
    }
}
