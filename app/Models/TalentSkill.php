<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentSkill extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'effects',
    ];

    protected $casts = [
        'effects' => 'array',
    ];
}
