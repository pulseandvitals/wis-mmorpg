<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cards')->insert([
            [
                'name' => 'Iron Skin Card',
                'description' => '+3 hp and +3 defense (From Frost Revenant)',
                'card_slot' => 1,
                'effects' => json_encode([
                    ['stat' => 'defense', 'operation' => 'fixed', 'value' => 3, 'value_type' => 'fixed'],
                    ['stat' => 'hp', 'operation' => 'fixed', 'value' => 3, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Blade Fury Card',
                'description' => '+2 Attack, +2 Attack Speed (From Orc Warrior)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'fixed'],
                    ['stat' => 'speed', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Arcane Pulse Card',
                'description' => '+20 Mana, +1 attack (From Rabitat)',
                'card_slot' => 3,
                'effects' => json_encode([
                    ['stat' => 'mp', 'operation' => 'fixed', 'value' => 20, 'value_type' => 'fixed'],
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Swift Step Card',
                'description' => '+1 Movement Speed, +1% evasion (From Cryptshade Walker)',
                'card_slot' => 4,
                'effects' => json_encode([
                    ['stat' => 'speed', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'fixed'],
                    ['stat' => 'evasion', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Blood Thirst Card',
                'description' => '+1% crit, +10 hp (From Hellfire Behemoth)',
                'card_slot' => 4,
                'effects' => json_encode([
                    ['stat' => 'crit', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                    ['stat' => 'hp', 'operation' => 'fixed', 'value' => 10, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Titan Guard Card',
                'description' => '+20 Defense (From Goblin Grunt)',
                'card_slot' => 3,
                'effects' => json_encode([
                    ['stat' => 'defense', 'operation' => 'fixed', 'value' => 20, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Wind Walker Card',
                'description' => '+4 Attack Speed, +8 Movement Speed (From Thunder Drake)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'speed', 'operation' => 'fixed', 'value' => 4, 'value_type' => 'fixed'],
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Stone Heart Card',
                'description' => '+30 hp, +1 defense (From Orc Zombie)',
                'card_slot' => 1,
                'effects' => json_encode([
                    ['stat' => 'hp', 'operation' => 'fixed', 'value' => 30, 'value_type' => 'fixed'],
                    ['stat' => 'defense', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Shadow Strike Card',
                'description' => '+6 attack, +1% Crit Damage (From Venomspine Lurker)',
                'card_slot' => 1,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 6, 'value_type' => 'fixed'],
                    ['stat' => 'crit', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Elemental Flow Card',
                'description' => '+10 attack (From Shadow Stalker)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 10, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Guardian Angel Card',
                'description' => '+12 hp, +12 defense (From StormHorn Guardian)',
                'card_slot' => 3,
                'effects' => json_encode([
                    ['stat' => 'hp', 'operation' => 'fixed', 'value' => 12, 'value_type' => 'fixed'],
                    ['stat' => 'defense', 'operation' => 'fixed', 'value' => 12, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Frostbite Card',
                'description' => '+1% stun, +1% evasion (From High Lizard)',
                'card_slot' => 4,
                'effects' => json_encode([
                    ['stat' => 'stun', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                    ['stat' => 'evasion', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Inferno Card',
                'description' => '+15 attack (From Boar Wrath)',
                'card_slot' => 4,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 15, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Storm Caller Card',
                'description' => '+1% crit, +1% evasion (From Holloweye Warden)',
                'card_slot' => 3,
                'effects' => json_encode([
                    ['stat' => 'crit', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                    ['stat' => 'evasion', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Vampiric Card',
                'description' => '+15 hp, +2 speed (From Mutant Sheep)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'hp', 'operation' => 'fixed', 'value' => 15, 'value_type' => 'fixed'],
                    ['stat' => 'speed', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Assassin Mark Card',
                'description' => '+2% Crit, +1% stun (From Blood Fang)',
                'card_slot' => 1,
                'effects' => json_encode([
                    ['stat' => 'crit', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'percent'],
                    ['stat' => 'stun', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Holy Light Card',
                'description' => '+2% evasion, +2 speed (From Ember Hog)',
                'card_slot' => 1,
                'effects' => json_encode([
                    ['stat' => 'evasion', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'percent'],
                    ['stat' => 'speed', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Earth Shaker Card',
                'description' => '+7 defense, +1% stun (From Zyrath Scaleborn)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'defense', 'operation' => 'fixed', 'value' => 7, 'value_type' => 'fixed'],
                    ['stat' => 'stun', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Void Walker Card',
                'description' => '+25 attack (From Dire Wolf)',
                'card_slot' => 3,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 25, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Dragon Scale Card',
                'description' => '+18 defense, +10 hp (From Nightveil Serpent)',
                'card_slot' => 4,
                'effects' => json_encode([
                    ['stat' => 'defense', 'operation' => 'fixed', 'value' => 18, 'value_type' => 'fixed'],
                    ['stat' => 'hp', 'operation' => 'fixed', 'value' => 10, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Lucky Star Card',
                'description' => '+1% evasion, +2% crit (From Frostbite Revenant)',
                'card_slot' => 1,
                'effects' => json_encode([
                    ['stat' => 'evasion', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                    ['stat' => 'crit', 'operation' => 'fixed', 'value' => 2, 'value_type' => 'percent'],
                ]),
            ],

            [
                'name' => 'Mythic Core Card',
                'description' => '+10 attack, +10 defense (From Doomclaw Titan)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 10, 'value_type' => 'fixed'],
                ]),
            ],

            [
                'name' => 'Heart Stopper Card',
                'description' => '+5 attack, +1% stun chance (From Grimscale Devourer)',
                'card_slot' => 2,
                'effects' => json_encode([
                    ['stat' => 'attack', 'operation' => 'fixed', 'value' => 5, 'value_type' => 'fixed'],
                    ['stat' => 'stun', 'operation' => 'fixed', 'value' => 1, 'value_type' => 'percent'],
                ]),
            ],
        ]);
    }
}
