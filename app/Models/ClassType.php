<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_health',
        'base_mana',
        'base_attack',
        'base_defense',
        'base_speed',
        'base_evasion',
        'base_critical',
        'wields',
    ];
}
