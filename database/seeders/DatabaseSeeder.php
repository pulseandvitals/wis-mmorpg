<?php

namespace Database\Seeders;

use CardSeeder;
use Database\Seeders\CardSeeder as SeedersCardSeeder;
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
        $this->call(GearSeeder::class);
        $this->call(WeaponSeeder::class);
        $this->call(CraftMaterial::class);
        $this->call(PotionSeeder::class);
        $this->call(WingSeeder::class);
        $this->call(TalentSkillSeeder::class);
        $this->call(CardSlotSeeder::class);
        $this->call(BossMonsterSeeder::class);
    }
}
