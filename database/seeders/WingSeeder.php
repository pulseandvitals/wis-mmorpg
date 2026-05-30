<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gears')->insert([
            [
                'name' => 'Ember Wings',
                'type' => 'wing',
                'basic_stats' => json_encode([
                    'attack' => 5,
                    'defense' => 2,
                ]),
                'requirement_level' => 15,
            ],
            [
                'name' => 'Azure Wings',
                'type' => 'wing',
                'basic_stats' => json_encode([
                    'attack' => 3,
                    'speed' => 2,
                ]),
                'requirement_level' => 15,
            ],
            [
                'name' => 'Angelus Wings',
                'type' => 'wing',
                'basic_stats' => json_encode([
                    'hp' => 30,
                    'mp' => 15,
                ]),
                'requirement_level' => 15,
            ],
            [
                'name' => 'Celestial Wings',
                'type' => 'wing',
                'basic_stats' => json_encode([
                    'crit' => 1,
                    'defense' => 3,
                ]),
                'requirement_level' => 15,
            ],
            [
                'name' => 'Shadow Wings',
                'type' => 'wing',
                'basic_stats' => json_encode([
                    'evasion' => 1,
                    'attack' => 3,
                ]),
                'requirement_level' => 15,
            ],
        ]);
    }
}
