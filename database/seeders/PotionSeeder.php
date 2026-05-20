<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PotionSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        $potions = [
            [
                'name' => 'Recovery Potion',
                'description' => 'Restores 500 HP and 250 MP over 10 seconds',
                'type' => 'hot',
                'effect' => json_encode([
                        ['stat' => 'hp', 'value' => 500, 'type' => 'fixed'],
                        ['stat' => 'mp', 'value' => 250, 'type' => 'fixed'],
                ]),
                'item_price' => 500,
                'sell_type' => 'gold',
            ],
            [
                'name' => 'Attack Potion',
                'description' => 'Increases attack power by 20% for 1 hour',
                'type' => 'buff',
                'effect' => json_encode([
                    'stat' => 'attack',
                    'value' => 20,
                    'value_type' => 'percentage',
                    'duration' => 1, // seconds
                    'is_percentage' => true
                ]),
                'item_price' => 30,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'Defense Potion',
                'description' => 'Increases defense by 20% for 1 hr',
                'type' => 'buff',
                'effect' => json_encode([
                    'stat' => 'defense',
                    'value' => 20,
                    'value_type' => 'percentage',
                    'duration' => 1,
                    'is_percentage' => true
                ]),
                'item_price' => 30,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'HP Potion',
                'description' => 'Increases HP by 20% for 1 hr',
                'type' => 'instant',
                'effect' => json_encode([
                    'stat' => 'hp',
                    'value' => 20,
                    'value_type' => 'percentage',
                    'duration' => 1,
                    'is_percentage' => true
                ]),
                'item_price' => 30,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'Critical Potion',
                'description' => 'Increases critical rate by 20% for 1 hr ',
                'type' => 'buff',
                'effect' => json_encode([
                    'stat' => 'crit_rate',
                    'value' => 20,
                    'value_type' => 'percentage',
                    'duration' => 1,
                    'is_percentage' => true
                ]),
                'item_price' => 40,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'Evasion Potion',
                'description' => 'Increases evasion by 20% for 1 hr',
                'type' => 'buff',
                'effect' => json_encode([
                    'stat' => 'evasion',
                    'value' => 20,
                    'value_type' => 'percentage',
                    'duration' => 1,
                    'is_percentage' => true
                ]),
                'item_price' => 40,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'Speed Potion',
                'description' => 'Increases movement speed by 30% for 1 hr',
                'type' => 'buff',
                'effect' => json_encode([
                    'stat' => 'movement_speed',
                    'value' => 30,
                    'value_type' => 'percentage',
                    'duration' => 1,
                    'is_percentage' => true
                ]),
                'item_price' => 40,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'EXP Potion',
                'description' => 'Doubles experience gained for 3 hrs',
                'type' => 'buff',
                'effect' => json_encode([
                    'stat' => 'experience_rate',
                    'value' => 100,
                    'value_type' => 'percentage',
                    'duration' => 3,
                    'is_percentage' => true,
                    'multiplier' => 2
                ]),
                'item_price' => 60,
                'sell_type' => 'diamond',
            ],
            [
                'name' => 'Secret Potion',
                'description' => 'Random effect! Could be good or bad...',
                'type' => 'random',
                'effect' => json_encode([
                    'possible_effects' => [
                        ['stat' => 'hp', 'value' => 500, 'type' => 'heal', 'chance' => 20],
                        ['stat' => 'attack', 'value' => 30, 'type' => 'buff', 'chance' => 15],
                        ['stat' => 'defense', 'value' => 30, 'type' => 'buff', 'chance' => 15],
                        ['stat' => 'hp', 'value' => 200, 'type' => 'damage', 'chance' => 10],
                        ['stat' => 'attack', 'value' => 20, 'type' => 'debuff', 'chance' => 10],
                        ['type' => 'nothing', 'chance' => 30]
                    ],
                    'duration' => 1
                ]),
                'item_price' => 50,
                'sell_type' => 'diamond',
            ],
        ];

        foreach ($potions as $potion) {
            DB::table('potions')->insert([
                'name' => $potion['name'],
                'description' => $potion['description'],
                'type' => $potion['type'],
                'effect' => $potion['effect'],
                'item_price' => $potion['item_price'],
                'sell_type' => $potion['sell_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
