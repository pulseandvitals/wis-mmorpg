<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartySharedReward implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $player_id;
    public $exp;
    public $gold;

    public function __construct($data)
    {
        $this->player_id = $data['player_id'];
        $this->exp = $data['exp'];
        $this->gold = $data['gold'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('party.'.$this->player_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'party.reward';
    }

    public function broadcastWith(): array
    {
        return [
            'player_id' => $this->player_id,
            'exp' => $this->exp,
            'gold' => $this->gold,
        ];
    }
}
