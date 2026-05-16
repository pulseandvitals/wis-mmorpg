<?php

namespace App\Http\Controllers;

use App\Models\WorldChat;
use Illuminate\Http\Request;

class WorldChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'msg_value' => 'required|string|max:255',
        ]);

        $player = auth()->user()->player;
        $worldChat = new WorldChat();
        $worldChat->player_id = $player->id;
        $worldChat->message = $request->msg_value;
        $worldChat->save();

        return response()->json([
            'status' => 202,
        ]);
    }

    public function getWorldChat()
    {
        return response()->stream(function () {
        while (true) {
            $messages = WorldChat::with('player:id,name')
                ->latest()
                ->limit(10)
                ->get()
                ->reverse()
                ->values();

                echo "data: " . json_encode($messages) . "\n\n";

                ob_flush();
                flush();

                usleep(15000);

            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
