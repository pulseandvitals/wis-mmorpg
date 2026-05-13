<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $fillable = [
        'map_id',
        'name',
        'description',
        'exp_multiplier',
        'level_requirement',
        'is_safe_zone',
    ];
}
