<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZoneStateUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $payload;
    public $mapId;

    public function __construct($mapId, $payload)
    {
        $this->mapId = $mapId;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new Channel('zone.' . $this->mapId);
    }

    public function broadcastAs()
    {
        return 'zone.state.updated';
    }
}
