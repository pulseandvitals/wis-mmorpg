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

        function randStats(float|int $value, float $variance = 0.1): int
        {
            $min = $value * (1 - $variance);
            $max = $value * (1 + $variance);

            return (int) random_int((int) $min, (int) $max);
        }

        for ($level = 1; $level <= 50; $level += ($level == 1 ? 9 : 10)) {

            foreach ($types as $type) {

                $stats = match ($type) {

                    // DAMAGE
                    'weapon' => [
                        'attack' => randStats($level * 1.2, 0.12),
                        'crit_rate' => randStats($level / 10, 0.15),
                    ],

                    // MEDIUM DEFENSE
                    'helmet' => [
                        'hp' => randStats($level * 4, 0.10),
                        'defense' => randStats($level * 0.4, 0.12),
                    ],

                    // MAIN TANK ITEM
                    'armor' => [
                        'hp' => randStats($level * 8, 0.12),
                        'defense' => randStats($level * 0.8, 0.10),
                    ],

                    // LOWER BODY DEFENSE
                    'pants' => [
                        'hp' => randStats($level * 5, 0.10),
                        'defense' => randStats($level * 0.5, 0.12),
                    ],

                    // DPS UTILITY
                    'gloves' => [
                        'attack' => randStats($level * 0.4, 0.12),
                        'attack_speed' => randStats($level / 10, 0.20),
                    ],

                    // MOBILITY
                    'boots' => [
                        'speed' => randStats($level * 0.1, 0.15),
                        'evasion' => randStats($level * 0.2, 0.15),
                    ],

                    // DEFENSIVE ITEM
                    'shield' => [
                        'defense' => randStats($level * 1.0, 0.10),
                        'block_rate' => randStats($level * 0.3, 0.15),
                    ],

                    // ACCESSORY / HYBRID BONUS
                    'ring' => [
                        'crit_rate' => randStats($level * 0.2, 0.20),
                        'attack' => randStats($level * 0.3, 0.15),
                        'hp' => randStats($level * 2, 0.10),
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
