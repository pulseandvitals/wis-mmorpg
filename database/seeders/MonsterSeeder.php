<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MonsterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseMonsters = [
            [
                'name' => 'Frost Revenant',
                'map' => 'Valdora Grassland',
                'element' => 'water',
                'max_hp' => 50,
                'skill' => ['name' => 'Frozen Soul Burst', 'damage' => 5],
                'drops' => [
                    ['item' => 'Ice Crystal', 'chance' => 12],
                    ['item' => 'Frozen Core', 'chance' => 5],
                ],
                'exp' => 15
            ],

            [
                'name' => 'Orc Warrior',
                'map' => 'Valdora Grassland',
                'element' => 'earth',
                'max_hp' => 70,
                'skill' => ['name' => 'War Cleave', 'damage' => 8],
                'drops' => [
                    ['item' => 'Orc Axe', 'chance' => 8],
                    ['item' => 'War Badge', 'chance' => 10],
                ],
                'exp' => 18
            ],

            [
                'name' => 'Rabitat',
                'map' => 'Valdora Grassland',
                'element' => 'wind',
                'max_hp' => 60,
                'skill' => ['name' => 'Feral Kick', 'damage' => 6],
                'drops' => [
                    ['item' => 'Rabbit Fur', 'chance' => 15],
                    ['item' => 'Small Meat', 'chance' => 8],
                ],
                'exp' => 16
            ],

            [
                'name' => 'Hellfire Behemoth',
                'map' => 'Dark Forest',
                'element' => 'fire',
                'max_hp' => 130,
                'skill' => ['name' => 'Inferno Collapse', 'damage' => 15],
                'drops' => [
                    ['item' => 'Hell Core', 'chance' => 3],
                    ['item' => 'Burning Gem', 'chance' => 5],
                ],
                'exp' => 30
            ],

            [
                'name' => 'Goblin Grunt',
                'map' => 'Dark Forest',
                'element' => 'earth',
                'max_hp' => 140,
                'skill' => ['name' => 'Rage Smash', 'damage' => 16],
                'drops' => [
                    ['item' => 'Goblin Ear', 'chance' => 10],
                    ['item' => 'Rusty Shield', 'chance' => 6],
                ],
                'exp' => 25
            ],

            [
                'name' => 'Thunder Drake',
                'map' => 'Dark Forest',
                'element' => 'electric',
                'max_hp' => 170,
                'skill' => ['name' => 'Thunder Roar', 'damage' => 18],
                'drops' => [
                    ['item' => 'Lightning Core', 'chance' => 3],
                    ['item' => 'Dragon Scale', 'chance' => 6],
                ],
                'exp' => 27
            ],

            [
                'name' => 'Orc Zombie',
                'map' => 'Dark Forest',
                'element' => 'wind',
                'max_hp' => 140,
                'skill' => ['name' => 'Rot Slam', 'damage' => 16],
                'drops' => [
                    ['item' => 'Rot Flesh', 'chance' => 12],
                    ['item' => 'Zombie Bone', 'chance' => 8],
                ],
                'exp' => 27
            ],

            [
                'name' => 'Shadow Stalker',
                'map' => 'Crystal Cave',
                'element' => 'wind',
                'max_hp' => 215,
                'skill' => ['name' => 'Shadow Execution', 'damage' => 22],
                'drops' => [
                    ['item' => 'Dark Cloth', 'chance' => 6],
                    ['item' => 'Shadow Dagger', 'chance' => 2],
                ],
                'exp' => 55
            ],

            [
                'name' => 'StormHorn Guardian',
                'map' => 'Crystal Cave',
                'element' => 'electric',
                'max_hp' => 225,
                'skill' => ['name' => 'Storm Charge', 'damage' => 30],
                'drops' => [
                    ['item' => 'Horn Fragment', 'chance' => 5],
                    ['item' => 'Storm Essence', 'chance' => 2],
                ],
                'exp' => 60
            ],

            [
                'name' => 'High Lizard',
                'map' => 'Crystal Cave',
                'element' => 'fire',
                'max_hp' => 260,
                'skill' => ['name' => 'Lizard Flame Bite', 'damage' => 40],
                'drops' => [
                    ['item' => 'Lizard Scale', 'chance' => 8],
                    ['item' => 'Fire Sac', 'chance' => 4],
                ],
                'exp' => 70
            ],

            [
                'name' => 'Boar Wrath',
                'map' => 'Crystal Cave',
                'element' => 'earth',
                'max_hp' => 200,
                'skill' => ['name' => 'Frenzy Charge', 'damage' => 38],
                'drops' => [
                    ['item' => 'Boar Tusks', 'chance' => 7],
                    ['item' => 'Wild Meat', 'chance' => 10],
                ],
                'exp' => 65
            ],

            [
                'name' => 'Mutant Sheep',
                'map' => 'Sky Islands',
                'element' => 'electric',
                'max_hp' => 360,
                'skill' => ['name' => 'Chaos Ram', 'damage' => 76],
                'drops' => [
                    ['item' => 'Mutant Wool', 'chance' => 6],
                    ['item' => 'Strange Meat', 'chance' => 4],
                ],
                'exp' => 110
            ],

            [
                'name' => 'Blood Fang',
                'map' => 'Sky Islands',
                'element' => 'water',
                'max_hp' => 360,
                'skill' => ['name' => 'Blood Bite', 'damage' => 65],
                'drops' => [
                    ['item' => 'Vampire Fang', 'chance' => 3],
                    ['item' => 'Blood Crystal', 'chance' => 4],
                ],
                'exp' => 100
            ],

            [
                'name' => 'Ember Hog',
                'map' => 'Sky Islands',
                'element' => 'fire',
                'max_hp' => 370,
                'skill' => ['name' => 'Flame Rush', 'damage' => 70],
                'drops' => [
                    ['item' => 'Burnt Meat', 'chance' => 10],
                    ['item' => 'Ember Core', 'chance' => 5],
                ],
                'exp' => 110
            ],

            [
                'name' => 'Zyrath Scaleborn',
                'map' => 'Sky Islands',
                'element' => 'wind',
                'max_hp' => 390,
                'skill' => ['name' => 'Scaleborn Apocalypse', 'damage' => 85],
                'drops' => [
                    ['item' => 'Ancient Scale', 'chance' => 1],
                    ['item' => 'Dragon Heart', 'chance' => 0.5],
                ],
                'exp' => 130
            ],

            [
                'name' => 'Dire Wolf',
                'map' => 'Sky Islands',
                'element' => 'wind',
                'max_hp' => 410,
                'skill' => ['name' => 'Moon Hunt', 'damage' => 73],
                'drops' => [
                    ['item' => 'Wolf Pelt', 'chance' => 12],
                    ['item' => 'Sharp Fang', 'chance' => 8],
                ],
                'exp' => 120
            ],
        ];

        /* =========================================================
        🔁 AUTO GENERATE UNDERGROUND (NO MANUAL DUPLICATION)
        ========================================================= */

        $monsters = [];

        foreach ($baseMonsters as $m) {

            // SURFACE
            $m['skill'] = json_encode($m['skill']);
            $m['drops'] = json_encode(array_merge($m['drops'], [
                ['item' => 'Celebeam Gem', 'chance' => 2],
                ['item' => 'Seleri Gem', 'chance' => 4],
            ]));

            $monsters[] = $m;

            // UNDERGROUND COPY
            $u = $m;

            $u['name'] .= ' (Elite)';
            $u['map'] .= ' Underground';
            $u['max_hp'] = (int)($m['max_hp'] * 1.8);

            $skill = json_decode($m['skill'], true);
            $skill['damage'] = (int)($skill['damage'] * 1.6);
            $skill['name'] .= '+';

            $u['skill'] = json_encode($skill);

            $drops = json_decode($m['drops'], true);

            foreach ($drops as &$d) {
                $d['chance'] = max(1, (int)($d['chance'] / 3));
            }

            $u['drops'] = json_encode($drops);

            $u['exp'] = (int)($m['exp'] * 2);

            $monsters[] = $u;
        }
    }
}
