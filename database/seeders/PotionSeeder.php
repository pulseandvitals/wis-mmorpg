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
            'description' => 'Restores 200 HP and 150 MP over 10 seconds',
            'type' => 'hot',
            'effect' => json_encode([
                [
                    'stat' => 'hp',
                    'operation' => 'heal',
                    'value' => 200,
                    'value_type' => 'flat',
                    'duration' => 10
                ],
                [
                    'stat' => 'mp',
                    'operation' => 'heal',
                    'value' => 150,
                    'value_type' => 'flat',
                    'duration' => 10
                ]
            ]),
            'item_price' => 500,
            'sell_type' => 'gold',
        ],

        [
            'name' => 'Attack Potion',
            'description' => 'Increases attack power by 20% for 1 hour',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'attack',
                    'operation' => 'multiply',
                    'value' => 20,
                    'value_type' => 'percent',
                    'duration' => 3600
                ]
            ]),
            'item_price' => 30,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'Defense Potion',
            'description' => 'Increases defense by 20% for 1 hr',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'defense',
                    'operation' => 'multiply',
                    'value' => 20,
                    'value_type' => 'percent',
                    'duration' => 3600
                ]
            ]),
            'item_price' => 30,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'HP Potion',
            'description' => 'Increases HP by 20% for 1 hr',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'hp',
                    'operation' => 'multiply',
                    'value' => 20,
                    'value_type' => 'percent',
                    'duration' => 3600
                ]
            ]),
            'item_price' => 30,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'Critical Potion',
            'description' => 'Increases critical rate by 20% for 1 hr',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'crit',
                    'operation' => 'multiply',
                    'value' => 20,
                    'value_type' => 'percent',
                    'duration' => 3600
                ]
            ]),
            'item_price' => 40,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'Evasion Potion',
            'description' => 'Increases evasion by 20% for 1 hr',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'evasion',
                    'operation' => 'multiply',
                    'value' => 20,
                    'value_type' => 'percent',
                    'duration' => 3600
                ]
            ]),
            'item_price' => 40,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'Speed Potion',
            'description' => 'Increases movement speed by 30% for 1 hr',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'speed',
                    'operation' => 'multiply',
                    'value' => 30,
                    'value_type' => 'percent',
                    'duration' => 3600
                ]
            ]),
            'item_price' => 40,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'EXP Potion',
            'description' => 'Doubles experience gained for 3 hrs',
            'type' => 'buff',
            'effect' => json_encode([
                [
                    'stat' => 'exp',
                    'operation' => 'multiply',
                    'value' => 100,
                    'value_type' => 'percent',
                    'duration' => 10800
                ]
            ]),
            'item_price' => 60,
            'sell_type' => 'diamond',
        ],

        [
            'name' => 'Secret Potion',
            'description' => 'Random effect! Could be good or bad...',
            'type' => 'random',
            'effect' => json_encode([
                [
                    'type' => 'random',
                    'duration' => null,
                    'candidates' => [
                        [
                            'chance' => 20,
                            'stat' => 'hp',
                            'operation' => 'heal',
                            'value' => 500,
                            'value_type' => 'flat'
                        ],
                        [
                            'chance' => 15,
                            'stat' => 'attack',
                            'operation' => 'multiply',
                            'value' => 30,
                            'value_type' => 'percent'
                        ],
                        [
                            'chance' => 15,
                            'stat' => 'defense',
                            'operation' => 'multiply',
                            'value' => 30,
                            'value_type' => 'percent'
                        ],
                        [
                            'chance' => 10,
                            'stat' => 'hp',
                            'operation' => 'damage',
                            'value' => 200,
                            'value_type' => 'flat'
                        ],
                        [
                            'chance' => 10,
                            'stat' => 'attack',
                            'operation' => 'debuff',
                            'value' => 20,
                            'value_type' => 'percent'
                        ],
                        [
                            'chance' => 30,
                            'type' => 'nothing'
                        ]
                    ]
                ]
            ]),
            'item_price' => 9999,
            'sell_type' => 'diamond',
        ],
    ];

        foreach ($potions as $potion) {
            DB::table('potions')->insert([
                'name' => $potion['name'],
                'description' => $potion['description'],
                'type' => $potion['type'],
                'effects' => $potion['effect'],
                'item_price' => $potion['item_price'],
                'sell_type' => $potion['sell_type'],
            ]);
        }
    }
}
