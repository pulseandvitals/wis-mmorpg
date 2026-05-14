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
            'weapon',
            'helmet',
            'armor',
            'gloves',
            'boots',
            'shield',
        ];

        $levelNames = [
            10 => 'Ashen',
            20 => 'Stormfang',
            30 => 'Dragonbane',
            40 => 'Shadowreign',
            50 => 'Celestium',
        ];

        for ($level = 10; $level <= 50; $level += 10) {

            foreach ($types as $type) {

                $stats = match ($type) {

                    // DAMAGE
                    'weapon' => [
                        'attack' => (int) ($level * 1.2),
                        'crit_rate' => (int) ($level / 10),
                    ],

                    // MEDIUM DEFENSE
                    'helmet' => [
                        'hp' => $level * 4,
                        'defense' => (int) ($level * 0.4),
                    ],

                    // MAIN TANK ITEM
                    'armor' => [
                        'hp' => $level * 8,
                        'defense' => (int) ($level * 0.8),
                    ],

                    // DPS UTILITY
                    'gloves' => [
                        'attack' => (int) ($level * 0.4),
                        'attack_speed' => (int) ($level / 10),
                    ],

                    // MOBILITY
                    'boots' => [
                        'speed' => (int) ($level * 0.1),
                        'evasion' => (int) ($level * 0.2),
                    ],

                    // DEFENSIVE ITEM
                    'shield' => [
                        'defense' => (int) ($level * 1),
                        'block_rate' => (int) ($level * 0.3),
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
