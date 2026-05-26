<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PvpResolved implements ShouldBroadcastNow
{
    public $battle;
    public $challengerEvent;
    public $opponentEvent;
    public $challengerHp;
    public $opponentHp;

    public function __construct($battle, $challengerEvent, $opponentEvent, $challengerHp, $opponentHp)
    {
        $this->battle = $battle;
        $this->challengerEvent = $challengerEvent;
        $this->opponentEvent = $opponentEvent;
        $this->challengerHp = $challengerHp;
        $this->opponentHp = $opponentHp;
    }

    public function broadcastOn()
    {
        return new Channel('pvp.' . $this->battle->id);
    }

    public function broadcastAs()
    {
        return 'pvp.round';
    }

    public function broadcastWith()
    {
        return [
            'battle_id' => $this->battle->id,

            // IMPORTANT: must match frontend keys EXACTLY
            'challenger_event' => $this->challengerEvent,
            'opponent_event' => $this->opponentEvent,

            'challenger_hp' => $this->challengerHp,
            'opponent_hp' => $this->opponentHp,

            'challenger_id' => $this->battle->challenger_id,
            'opponent_id' => $this->battle->opponent_id,

            'round' => $this->battle->round,
            'battle_ended' => $this->battle->status === 'finished',
            'winner_id' => $this->battle->winner_id,
        ];
    }
}
