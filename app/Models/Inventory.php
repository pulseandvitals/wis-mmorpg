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
        'enhancement_level'
    ];

    public function item()
    {
        return $this->belongsTo(Material::class, 'item_id');
    }

    public function getItemData()
    {
        return match ($this->item_type) {
            'gear' => $this->gear,
            'material' => $this->material,
            'potion' => $this->potion,
            default => null,
        };
    }

    public function gear()
    {
        return $this->belongsTo(Gear::class, 'item_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'item_id');
    }
}
