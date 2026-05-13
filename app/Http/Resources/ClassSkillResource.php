<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassSkillResource extends JsonResource
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
            'class' => $this->class,
            'name' => $this->name,
            'description' => $this->description,
            'target' => $this->target,
            'element' => $this->element,
            'requirement_level' => $this->requirement_level,
            'damage' => $this->damage,
            'mana_cost' => $this->mana_cost,
            'icon_path' => $this->icon_path
        ];
    }
}
