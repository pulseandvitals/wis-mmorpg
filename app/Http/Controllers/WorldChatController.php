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

        return redirect()->back();
   }
}
