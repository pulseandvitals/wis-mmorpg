<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MapResource extends JsonResource
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
            'map_id' => $this->map_id,
            'name' => $this->name,
            'description' => $this->description,
            'level_requirement' => $this->level_requirement,
            'is_safe_zone' => $this->is_safe_zone
        ];
    }
}
