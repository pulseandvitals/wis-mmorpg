<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'class_type_id',
        'current_level',
        'current_experience',
        'max_health',
        'current_health',
        'max_mana',
        'current_mana',
        'current_gold',
        'current_diamond',
        'total_attack',
        'total_defense',
        'total_speed',
        'total_evasion_percentage',
        'total_critical_percentage',
        'current_map_id',
        'x',
        'y',
        'helm_id',
        'armor_id',
        'pants_id',
        'boots_id',
        'gloves_id',
        'weapon_id',
        'shield_id',
        'accessory_id',
        'is_online',
        'user_id',
    ];

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}
