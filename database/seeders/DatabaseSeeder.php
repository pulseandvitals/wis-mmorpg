<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LevelExperienceSeeder::class);
        $this->call(MonsterSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(MapSeeder::class);
        $this->call(MaterialSeeder::class);
    }
}
