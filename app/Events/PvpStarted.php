<?php

namespace App\Events;

use App\Http\Resources\BattlePlayerResource;
use App\Http\Resources\PlayerResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PvpStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public $battle,
        public $attacker,
        public $defender
    ) {}

    public function broadcastOn()
    {
        return [
            new Channel("player.{$this->attacker->id}"),
            new Channel("player.{$this->defender->id}")
        ];
    }

    public function broadcastAs()
    {
        return 'pvp.started';
    }

    public function broadcastWith()
    {
        return [
            'battle' => $this->battle,
            'attacker' => new BattlePlayerResource($this->attacker),
            'defender' => new BattlePlayerResource($this->defender),
        ];
    }
}
