<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $fillable = [
        'name',
        'map',
        'element',
        'max_hp',
        'skill',
        'drops',
        'exp'
    ];
}
