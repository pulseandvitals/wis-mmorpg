<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gear extends Model
{
    protected $fillable = [
        'name',
        'type',
        'attack_bonus',
        'defense_bonus',
        'speed_bonus',
        'health_bonus',
        'mana_bonus',
        'evasion_bonus',
        'critical_bonus',
        'required_level',
    ];
}
