<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'class',
        'name',
        'description',
        'target',
        'element',
        'requirement_level',
        'damage',
        'mana_cost',
        'icon_path'
    ];


    public function scopeByClass($query, string $class)
    {
        return $query->where('class', $class);
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('requirement_level', '<=', $level);
    }
}
