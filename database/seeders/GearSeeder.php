<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GearSeeder extends Seeder
{
    public function run(): void
    {
        $gears = [];

        $types = [
            'helmet',
            'armor',
            'gloves',
            'boots',
            'pants',
            'shield',
            'ring'
        ];

        $levelNames = [
            1 => 'Karma',
            10 => 'Ashen',
            20 => 'Stormfang',
            30 => 'Dragonbane',
            40 => 'Shadowreign',
            50 => 'Celestium',
        ];

        function randStats(float|int $value): int
        {
            return (int) $value + random_int(1, 5);
        }

        for ($level = 1; $level <= 50; $level += ($level == 1 ? 9 : 10)) {

            foreach ($types as $type) {

                $stats = match ($type) {

                    // MEDIUM DEFENSE
                    'helmet' => [
                        'hp' => randStats($level * 1.3),
                        'defense' => randStats($level * 0.4),
                    ],

                    // MAIN TANK ITEM
                    'armor' => [
                        'hp' => randStats($level * 1.4),
                        'defense' => randStats($level * 0.6),
                    ],

                    // LOWER BODY DEFENSE
                    'pants' => [
                        'hp' => randStats($level * 1.5),
                        'defense' => randStats($level * 0.6),
                    ],

                    // DPS UTILITY
                    'gloves' => [
                        'attack' => randStats($level * 0.4),
                        'speed' => randStats($level * 0.6),
                    ],

                    // MOBILITY
                    'boots' => [
                        'speed' => randStats($level * 0.4),
                        'evasion' => randStats($level * 0.1),
                    ],

                    // DEFENSIVE ITEM
                    'shield' => [
                        'defense' => randStats($level * 0.8),
                        'evasion' => randStats($level * 0.1),
                    ],

                    // ACCESSORY / HYBRID BONUS
                    'ring' => [
                        'crit' => randStats($level * 0.1),
                        'attack' => randStats($level * 0.2),
                        'hp' => randStats($level * 0.8),
                    ],

                    default => [],
                };

                $gearName = $levelNames[$level] . ' ' . ucfirst($type);

                $gears[] = [
                    'name' => $gearName,
                    'type' => $type,
                    'basic_stats' => json_encode($stats),
                    'requirement_level' => $level,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('gears')->insert($gears);
    }
}
