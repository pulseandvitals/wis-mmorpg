<?php

namespace App\Models;

use App\Http\Resources\CardResource;
use App\Http\Resources\GearResource;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'player_id',
        'item_id',
        'item_type',
        'quantity',
        'is_equipped',
        'random_stat',
        'enhancement_level'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'item_id');
    }

    public function getItemData()
    {
        return match ($this->item_type) {
            'gear' => GearResource::make($this->gear),
            'material' => $this->material,
            'potion' => $this->potion,
            'card' => CardResource::make($this->card),
            default => null,
        };
    }

    public function gear()
    {
        return $this->belongsTo(Gear::class, 'item_id');
    }

    public function potion()
    {
        return $this->belongsTo(Potion::class, 'item_id');
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'item_id');
    }
}
