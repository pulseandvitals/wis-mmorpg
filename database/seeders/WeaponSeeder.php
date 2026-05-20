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

        function randStat(float|int $value): int
        {
            return (int) $value + random_int(1, 5);
        }


        foreach ($levelNames as $level => $prefix) {

            foreach ($weaponTypes as $weaponType) {

                $stats = [
                    'attack' => randStat($level * 2),
                    'crit' => randStat($level * 0.2),
                    'speed' => randStat($level * 0.3),
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
