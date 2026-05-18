<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CraftingMaterial extends Model
{
    protected $fillable = [
        'data'
    ];

    protected $cast  = [
        'materials' => 'array',
    ];
}
