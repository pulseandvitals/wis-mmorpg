<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $playerId;
    public string $name;
    public string $class_type;
    public int $current_map_id;
    public int $x;
    public int $y;
    public string $direction;

    public function __construct($player)
    {
        $this->playerId = $player->id;
        $this->name = $player->name;
        $this->class_type = $player->class_type;
        $this->current_map_id = $player->current_map_id;
        $this->x = $player->x;
        $this->y = $player->y;
        $this->direction = $player->direction;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('world'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'player.moved';
    }

    public function broadcastWith(): array
    {
        return [
            'player_id' => $this->playerId,
            'name' => $this->name,
            'class_type' => $this->class_type,
            'current_map_id' => $this->current_map_id,
            'x' => $this->x,
            'y' => $this->y,
            'direction' => $this->direction,
        ];
    }
}
