<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaponSeeder extends Seeder
{
    public function run(): void
    {
        $weapons = [];

        $levelNames = [
            10 => 'Bloodthorn',
            20 => 'Adamanta',
            30 => 'Hellforge',
            40 => 'Voidpiercer',
            50 => 'Starforged',
        ];

        $weaponTypes = [
            'Sword',
            'Spear',
            'Bow',
            'Staff',
            'Dagger',
        ];

        function randStat(float|int $value, float $variance = 0.1): int
        {
            $min = $value * (1 - $variance);
            $max = $value * (1 + $variance);

            return (int) random_int((int) $min, (int) $max);
        }


        foreach ($levelNames as $level => $prefix) {

            foreach ($weaponTypes as $weaponType) {

                $stats = [
                    'attack' => randStat($level * 1.2, 0.12),
                    'crit_rate' => randStat($level / 10, 0.15),
                    'attack_speed' => randStat($level / 10, 0.12),
                ];

                $weapons[] = [
                    'name' => $prefix . ' ' . $weaponType,
                    'type' => 'weapon',
                    'basic_stats' => json_encode($stats),
                    'requirement_level' => $level,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('gears')->insert($weapons);
    }
}
