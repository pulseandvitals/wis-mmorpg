<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Potion extends Model
{
    protected $fillable = [
        'name',
        'type',
        'health_restore',
        'mana_restore',
        'attack_bonus',
        'defense_bonus',
        'speed_bonus',
        'evasion_bonus',
        'critical_bonus',
    ];

    protected $casts = [
        'effects' => 'array',
    ];
}
