<?php

namespace Database\Seeders;

use App\Models\Map;
use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CREATE USER
        $user = User::updateOrCreate(
            ['email' => 'admin@game.com'],
            [
                'email' => 'admin@game.com',
                'name' => '[GM] Belialuin',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // CREATE PLAYER
        $player = Player::updateOrCreate(
            ['name' => '[GM] Belialuin'],
            [
                'class_type' => 'Crusader',

                'current_level' => 50,
                'current_experience' => 0,

                'max_health' => 500,
                'current_health' => 500,

                'max_mana' => 500,
                'current_mana' => 500,

                'current_gold' => 9999,
                'current_diamond' => 9999,

                'total_attack' => 15,
                'total_defense' => 15,
                'total_speed' => 15,

                'total_evasion_percentage' => 15,
                'total_critical_percentage' => 15,
                'total_stun_percentage' => 15,
                'current_map_id' => Map::where('name','Wisteria Town')->value('map_id'),

                'x' => 10,
                'y' => 10,
                'direction' => 'down',
            ]
        );

        $user->player_id = $player->id;
        $user->save();
    }
}
