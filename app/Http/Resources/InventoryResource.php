<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'player_id' => $this->player_id,
            'item_type' => $this->item_type,
            'quantity' => $this->quantity,
            'random_stat' => $this->random_stat,
            'enhancement_level' => $this->enhancement_level,
            'is_equipped' => $this->is_equipped,
            'item' => $this->getItemData(),
        ];
    }
}
