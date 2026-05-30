<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exp = 0;
$experiences = [];

for ($level = 1; $level <= 50; $level++) {

    $experiences[] = [
        'level' => $level,
        'required_experience' => $exp
    ];

    if ($level >= 1 && $level < 10) {
        // Early game
        $exp += rand(50, 80);

    } elseif ($level >= 10 && $level <= 20) {
        // Mid game
        $exp += rand(150, 250);

    } elseif ($level >= 20 && $level <= 30) {
        // Late-mid game
        $exp += rand(600, 850);

    } else {
        // End game
        $exp += floor(70 * pow($level, 1.18));
    }
}

DB::table('experiences')->insert($experiences);
    }
}
