<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuildMemberResource extends JsonResource
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
            'player' => $this->player,
            'rank' => $this->rank,
            'gold_contribution' => $this->gold_contribution,
            'joined_at' => $this->joined_at,
        ];
    }
}
