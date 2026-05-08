<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'player_id',
        'item_id',
        'item_type',
        'quantity',
        'is_equipped',
    ];
}
