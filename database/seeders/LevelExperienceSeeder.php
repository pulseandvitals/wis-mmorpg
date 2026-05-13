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
            $experiences[] = ['level' => $level, 'required_experience' => $exp];
            if ($level < 50) {
                $exp += floor(80 * pow($level, 1.75));
            }
        }
        DB::table('experiences')->insert($experiences);
    }
}
