<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class MonsterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monsters = [
            [
                'name' => 'Frost Revenant',
                'map' => 'Valdora Grassland',
                'element' => 'water',
                'max_hp' => 50,
                'skill' => json_encode(['name' => 'Frozen Soul Burst', 'damage' => 5]),
                'drops' => json_encode([
                    ['item' => 'Ice Crystal', 'chance' => 50],
                    ['item' => 'Frozen Core', 'chance' => 20],
                ]),
                'exp' => 15
            ],

            [
                'name' => 'Orc Warrior',
                'element' => 'earth',
                'map' => 'Valdora Grassland',
                'max_hp' => 70,
                'skill' => json_encode(['name' => 'War Cleave', 'damage' => 8]),
                'drops' => json_encode([
                    ['item' => 'Orc Axe', 'chance' => 30],
                    ['item' => 'War Badge', 'chance' => 50],
                ]),
                'exp' => 18
            ],

            [
                'name' => 'Rabitat',
                'element' => 'wind',
                'map' => 'Valdora Grassland',
                'max_hp' => 60,
                'skill' => json_encode(['name' => 'Feral Kick', 'damage' => 6]),
                'drops' => json_encode([
                    ['item' => 'Rabbit Fur', 'chance' => 70],
                    ['item' => 'Small Meat', 'chance' => 30],
                ]),
                'exp' => 16
            ],

            [
                'name' => 'Hellfire Behemoth',
                'element' => 'fire',
                'map' => 'Dark Forest',
                'max_hp' => 130,
                'skill' => json_encode(['name' => 'Inferno Collapse', 'damage' => 15]),
                'drops' => json_encode([
                    ['item' => 'Hell Core', 'chance' => 10],
                    ['item' => 'Burning Gem', 'chance' => 25],
                ]),
                'exp' => 30
            ],

            [
                'name' => 'Goblin Grunt',
                'element' => 'earth',
                'map' => 'Dark Forest',
                'max_hp' => 140,
                'skill' => json_encode(['name' => 'Rage Smash', 'damage' => 16]),
                'drops' => json_encode([
                    ['item' => 'Goblin Ear', 'chance' => 60],
                    ['item' => 'Rusty Shield', 'chance' => 30],
                ]),
                'exp' => 25
            ],

            [
                'name' => 'Thunder Drake',
                'map' => 'Dark Forest',
                'element' => 'electric',
                'max_hp' => 170,
                'skill' => json_encode(['name' => 'Thunder Roar', 'damage' => 18]),
                'drops' => json_encode([
                    ['item' => 'Lightning Core', 'chance' => 15],
                    ['item' => 'Dragon Scale', 'chance' => 30],
                ]),
                'exp' => 27
            ],

            [
                'name' => 'Orc Zombie',
                'map' => 'Dark Forest',
                'element' => 'wind',
                'max_hp' => 140,
                'skill' => json_encode(['name' => 'Rot Slam', 'damage' => 16]),
                'drops' => json_encode([
                    ['item' => 'Rot Flesh', 'chance' => 75],
                    ['item' => 'Zombie Bone', 'chance' => 50],
                ]),
                'exp' => 27
            ],

            [
                'name' => 'Shadow Stalker',
                'map' => 'Crystal Cave',
                'element' => 'wind',
                'max_hp' => 215,
                'skill' => json_encode(['name' => 'Shadow Execution', 'damage' => 22]),
                'drops' => json_encode([
                    ['item' => 'Dark Cloth', 'chance' => 70],
                    ['item' => 'Shadow Dagger', 'chance' => 20],
                ]),
                'exp' => 55
            ],

            [
                'name' => 'StormHorn Guardian',
                'map' => 'Crystal Cave',
                'element' => 'electric',
                'max_hp' => 225,
                'skill' => json_encode(['name' => 'Storm Charge', 'damage' => 30]),
                'drops' => json_encode([
                    ['item' => 'Horn Fragment', 'chance' => 40],
                    ['item' => 'Storm Essence', 'chance' => 20],
                ]),
                'exp' => 60
            ],

            [
                'name' => 'High Lizard',
                'map' => 'Crystal Cave',
                'element' => 'fire',
                'max_hp' => 260,
                'skill' => json_encode(['name' => 'Lizard Flame Bite', 'damage' => 40]),
                'drops' => json_encode([
                    ['item' => 'Lizard Scale', 'chance' => 65],
                    ['item' => 'Fire Sac', 'chance' => 30],
                ]),
                'exp' => 70
            ],

            [
                'name' => 'Boar Wrath',
                'map' => 'Crystal Cave',
                'element' => 'earth',
                'max_hp' => 200,
                'skill' => json_encode(['name' => 'Frenzy Charge', 'damage' => 38]),
                'drops' => json_encode([
                    ['item' => 'Boar Tusks', 'chance' => 55],
                    ['item' => 'Wild Meat', 'chance' => 70],
                ]),
                'exp' => 65
            ],

            [
                'name' => 'Mutant Sheep',
                'map' => 'Sky Islands',
                'element' => 'electric',
                'max_hp' => 360,
                'skill' => json_encode(['name' => 'Chaos Ram', 'damage' => 76]),
                'drops' => json_encode([
                    ['item' => 'Mutant Wool', 'chance' => 60],
                    ['item' => 'Strange Meat', 'chance' => 35],
                ]),
                'exp' => 110
            ],

            [
                'name' => 'Blood Fang',
                'map' => 'Sky Islands',
                'element' => 'water',
                'max_hp' => 360,
                'skill' => json_encode(['name' => 'Blood Bite', 'damage' => 65]),
                'drops' => json_encode([
                    ['item' => 'Vampire Fang', 'chance' => 20],
                    ['item' => 'Blood Crystal', 'chance' => 25],
                ]),
                'exp' => 100
            ],

            [
                'name' => 'Ember Hog',
                'map' => 'Sky Islands',
                'element' => 'fire',
                'max_hp' => 370,
                'skill' => json_encode(['name' => 'Flame Rush', 'damage' => 70]),
                'drops' => json_encode([
                    ['item' => 'Burnt Meat', 'chance' => 80],
                    ['item' => 'Ember Core', 'chance' => 30],
                ]),
                'exp' => 110
            ],

            [
                'name' => 'Zyrath Scaleborn',
                'map' => 'Sky Islands',
                'element' => 'wind',
                'max_hp' => 390,
                'skill' => json_encode(['name' => 'Scaleborn Apocalypse', 'damage' => 85]),
                'drops' => json_encode([
                    ['item' => 'Ancient Scale', 'chance' => 10],
                    ['item' => 'Dragon Heart', 'chance' => 5],
                ]),
                'exp' => 130
            ],

            [
                'name' => 'Dire Wolf',
                'map' => 'Sky Islands',
                'element' => 'wind',
                'max_hp' => 410,
                'skill' => json_encode(['name' => 'Moon Hunt', 'damage' => 73]),
                'drops' => json_encode([
                    ['item' => 'Wolf Pelt', 'chance' => 70],
                    ['item' => 'Sharp Fang', 'chance' => 45],
                ]),
                'exp' => 120
            ],
        ];

        DB::table('monsters')->insert($monsters);
    }
}
