<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'class_type',
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
        'direction',
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

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function registerPlayer($name, $class_type, $user_id)
    {
        $this->name = $name;
        $this->class_type = $class_type;
        $this->current_level = 1;
        $this->current_experience = 0;
        $this->max_health = 100;
        $this->current_health = 100;
        $this->max_mana = 50;
        $this->current_mana = 50;
        $this->current_gold = 0;
        $this->current_diamond = 0;
        $this->total_attack = 10;
        $this->total_defense = 5;
        $this->total_speed = 5;
        $this->total_evasion_percentage = 5.0;
        $this->total_critical_percentage = 5.0;
        $this->current_map_id = Map::where('name', 'Town Square')->firstOrFail()->map_id;
        $this->x = 7;
        $this->y = 2;
        $this->direction = 'down';

        $data = self::create($this->attributesToArray());

        User::where('id', $user_id)->update(['player_id' => $data->id, 'role' => 'player']);
    }
}
