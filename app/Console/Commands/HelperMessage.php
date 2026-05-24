<?php

namespace App\Console\Commands;

use App\Models\WorldChat;
use Illuminate\Console\Command;

class HelperMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:helper-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper tips for all the players.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $helperMessages = [
                "💡 Upgrade your equipment regularly! Even a small stat boost can make dungeon runs much easier.",

                "⚔️ Elite monsters have a higher chance to drop rare equipment and crafting materials. Hunt carefully!",

                "🎯 Completing daily quests is one of the fastest ways to earn EXP and gold.",

                "✨ Enhance your gear to unlock greater power, but beware — failed upgrades may reduce durability!",

                "👥 Party up with other players to clear dungeons faster and gain bonus rewards.",

                "🗺️ Some secret areas contain hidden treasures, rare monsters, and powerful equipment.",

                "🛠️ Help us improve! Report any bugs, errors, or unusual behavior in our Discord community.",

                "🔥 Skills consume mana and have cooldowns. Time your abilities wisely during battle.",

                "💰 Rare items can be traded with other players for valuable gold and resources.",

                "🛡️ Defense is just as important as attack. Balance your equipment wisely.",

                "⚡ Higher level monsters give better rewards, but they are far more dangerous.",

                "🏹 Different weapon types have unique strengths. Experiment to find your best combat style.",

                "🌟 Join a guild to participate in guild wars, raids, and exclusive activities.",

                "⏳ Some world events only appear for a limited time. Stay alert for announcements!",

                "🎁 Login daily for daily events, rewards and useful items.",

                "🛠️ Found a bug? Please report it in our official Discord so we can fix it quickly and improve your experience!",
            ];

        WorldChat::create([
            'message' => $helperMessages[array_rand($helperMessages)],
            'channel' => 'world',
            'player_id' => null,
            'is_admin' => true,
        ]);
    }
}
