<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gear extends Model
{
    protected $fillable = [
        'name',
        'type',
        'basic_stats',
        'requirement_level',
    ];
}
