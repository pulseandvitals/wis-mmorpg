<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BossMonsterSeeder extends Seeder
{
    public function run(): void
    {
        $bosses = [
            [
                'name' => 'Malgrath',
                'map' => 'Valdora Grassland Underground',
                'element' => 'electric',
                'respawn_time' => 3600,
                'max_hp' => 2000,
                'skill' => json_encode([
                    'name' => 'Soul Requiem',
                    'damage' => 120
                ]),
                'drops' => json_encode([
                    ['item' => 'Celebeam Gem', 'chance' => 80],
                    ['item' => 'Seleri Gem', 'chance' => 80],
                ]),
                'exp' => 300
            ],

            [
                'name' => 'Vorthak',
                'map' => 'Dark Forest Underground',
                'element' => 'earth',
                'respawn_time' => 3600,
                'max_hp' => 4000,
                'skill' => json_encode([
                    'name' => 'Soul Drain',
                    'damage' => 260
                ]),
                'drops' => json_encode([
                    ['item' => 'Celebeam Gem', 'chance' => 80],
                    ['item' => 'Seleri Gem', 'chance' => 80],
                ]),
                'exp' => 300
            ],

            [
                'name' => 'Ashfang',
                'map' => 'Crystal Cave Underground',
                'element' => 'water',
                'respawn_time' => 3600,
                'max_hp' => 6000,
                'skill' => json_encode([
                    'name' => 'Inferno Breath',
                    'damage' => 290
                ]),
                'drops' => json_encode([
                    ['item' => 'Celebeam Gem', 'chance' => 80],
                    ['item' => 'Seleri Gem', 'chance' => 80],
                ]),
                'exp' => 400
            ],

            [
                'name' => "Gormek",
                'map' => 'Volcanic Wasteland Underground',
                'element' => 'fire',
                'respawn_time' => 3600,
                'max_hp' => 8500,
                'skill' => json_encode([
                    'name' => 'Earthshatter Slam',
                    'damage' => 390
                ]),
                'drops' => json_encode([
                    ['item' => 'Celebeam Gem', 'chance' => 80],
                    ['item' => 'Seleri Gem', 'chance' => 80],
                ]),
                'exp' => 400
            ],

            [
                'name' => 'Cryoneth',
                'map' => 'Sky Islands Underground',
                'element' => 'wind',
                'respawn_time' => 3600,
                'max_hp' => 9800,
                'skill' => json_encode([
                    'name' => 'Absolute Zero',
                    'damage' => 410
                ]),
                'drops' => json_encode([
                    ['item' => 'Celebeam Gem', 'chance' => 80],
                    ['item' => 'Seleri Gem', 'chance' => 80],
                ]),
                'exp' => 600
            ],

            [
                'name' => 'Zerathul',
                'map' => 'Sky Islands Underground',
                'element' => 'electric',
                'respawn_time' => 3600,
                'max_hp' => 12500,
                'skill' => json_encode([
                    'name' => 'Thunder Apocalypse',
                    'damage' => 550
                ]),
                'drops' => json_encode([
                    ['item' => 'Celebeam Gem', 'chance' => 80],
                    ['item' => 'Seleri Gem', 'chance' => 80],
                ]),
                'exp' => 600
            ]
        ];

        DB::table('monsters')->insert($bosses);
    }
}
