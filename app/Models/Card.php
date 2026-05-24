<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name',
        'description',
        'card_slot',
        'effects'
    ];

    protected $cast = [
        'effects' => 'array'
    ];
}
