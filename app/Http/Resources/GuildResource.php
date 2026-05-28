<?php

namespace App\Http\Resources;

use App\Models\GuildMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuildResource extends JsonResource
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
            'name' => $this->name,
            'leader' => $this->leader,
            'level' => $this->level,
            'gold_stash' => $this->gold_stash,
            'icon' => $this->icon,
            'members' => GuildMemberResource::collection($this->whenLoaded('members')),
        ];
    }
}
