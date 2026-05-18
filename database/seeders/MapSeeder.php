<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\URL;
use Nette\Utils\Random;

class MapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maps = [
            [
                'name' => 'Town Square',
                'description' => 'A peaceful town filled with lush greenery and gentle streams.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 1,
            ],
            [
                'name' => 'Valdora Grassland',
                'description' => 'A peaceful grassland filled with lush greenery and gentle streams.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 1,
            ],
            [
                'name' => 'Valdora Underground',
                'description' => 'A peaceful grassland filled with lush greenery and gentle streams.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 1,
            ],
            [
                'name' => 'Dark Forest',
                'description' => 'A dense and eerie forest shrouded in darkness, home to mysterious creatures.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 5,
            ],
            [
                'name' => 'Dark Forest Underground',
                'description' => 'A dense and eerie forest shrouded in darkness, home to mysterious creatures.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 5,
            ],
            [
                'name' => 'Crystal Cave',
                'description' => 'A network of glittering caves adorned with sparkling crystals and hidden treasures.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 15,
            ],
            [
                'name' => 'Crystal Cave Underground',
                'description' => 'A network of glittering caves adorned with sparkling crystals and hidden treasures.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 15,
            ],
            [
                'name' => 'Volcanic Wasteland',
                'description' => 'A harsh and desolate landscape scarred by volcanic activity, with rivers of lava and ash-filled skies.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 20,
            ],
            [
                'name' => 'Volcanic Wasteland Underground',
                'description' => 'A harsh and desolate landscape scarred by volcanic activity, with rivers of lava and ash-filled skies.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 20,
            ],
            [
                'name' => 'Sky Islands',
                'description' => 'Floating islands high above the clouds, where gravity is weak and the air is thin.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 30,
            ],
            [
                'name' => 'Sky Islands Underground',
                'description' => 'Floating islands high above the clouds, where gravity is weak and the air is thin.',
                'map_id' => random_int(10000000, 99999999),
                'is_safe_zone' => true,
                'level_requirement' => 30,
            ],
        ];

        \Illuminate\Support\Facades\DB::table('maps')->insert($maps);
    }
}
