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
        $experiences = [];

        $exp = 0;

        for ($level = 1; $level <= 50; $level++) {

            $experiences[] = [
                'level' => $level,
                'required_experience' => $exp
            ];

            if ($level >= 1 && $level <= 10) {
                // EASY CURVE (fast leveling)
                $exp += rand(60, 90); // 4–6 mobs early game

            } elseif ($level >= 11 && $level <= 20) {
                // MEDIUM CURVE (noticeable grind)
                $exp += rand(120, 200); // 3–5 mobs mid game

            } else {
                // HARD CURVE (late game grind like your original idea)
                $exp += floor(90 * pow($level, 1.65));
            }
        }

        DB::table('experiences')->insert($experiences);
    }
}
