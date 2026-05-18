<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CraftMaterial extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $craftingMaterials = [

            // =====================
            // LEVEL 1
            // =====================
            [
                'requirement_level' => 1,
                'materials' => [
                    ['item' => 'Rabbit Fur', 'qty' => 25],
                    ['item' => 'Small Meat', 'qty' => 30],
                    ['item' => 'Goblin Ear', 'qty' => 20],
                ]
            ],

            // =====================
            // LEVEL 10
            // =====================
            [
                'requirement_level' => 10,
                'materials' => [
                    ['item' => 'Orc Axe', 'qty' => 15],
                    ['item' => 'War Badge', 'qty' => 15],
                    ['item' => 'Ice Crystal', 'qty' => 30],
                    ['item' => 'Goblin Ear', 'qty' => 25],
                    ['item' => 'Frozen Core', 'qty' => 10],
                ]
            ],

            // =====================
            // LEVEL 20
            // =====================
            [
                'requirement_level' => 20,
                'materials' => [
                    ['item' => 'Burning Gem', 'qty' => 10],
                    ['item' => 'Dragon Scale', 'qty' => 12],
                    ['item' => 'Lightning Core', 'qty' => 8],
                    ['item' => 'Hell Core', 'qty' => 6],
                    ['item' => 'Rot Flesh', 'qty' => 25],
                    ['item' => 'Zombie Bone', 'qty' => 20],
                ]
            ],

            // =====================
            // LEVEL 30
            // =====================
            [
                'requirement_level' => 30,
                'materials' => [
                    ['item' => 'Dark Cloth', 'qty' => 20],
                    ['item' => 'Shadow Dagger', 'qty' => 8],
                    ['item' => 'Storm Essence', 'qty' => 10],
                    ['item' => 'Horn Fragment', 'qty' => 18],
                    ['item' => 'Fire Sac', 'qty' => 15],
                    ['item' => 'Lizard Scale', 'qty' => 22],
                ]
            ],

            // =====================
            // LEVEL 40
            // =====================
            [
                'requirement_level' => 40,
                'materials' => [
                    ['item' => 'Boar Tusks', 'qty' => 30],
                    ['item' => 'Wild Meat', 'qty' => 35],
                    ['item' => 'Mutant Wool', 'qty' => 25],
                    ['item' => 'Strange Meat', 'qty' => 28],
                    ['item' => 'Vampire Fang', 'qty' => 12],
                    ['item' => 'Blood Crystal', 'qty' => 15],
                ]
            ],

            // =====================
            // LEVEL 50
            // =====================
            [
                'requirement_level' => 50,
                'materials' => [
                    ['item' => 'Wolf Pelt', 'qty' => 30],
                    ['item' => 'Sharp Fang', 'qty' => 25],
                    ['item' => 'Ember Core', 'qty' => 20],
                    ['item' => 'Burning Gem', 'qty' => 18],
                    ['item' => 'Ancient Scale', 'qty' => 8],
                    ['item' => 'Dragon Heart', 'qty' => 2],
                    ['item' => 'Storm Essence', 'qty' => 18],
                    ['item' => 'Shadow Dagger', 'qty' => 15],
                ]
            ],
        ];

        foreach ($craftingMaterials as &$data) {
            $data['materials'] = json_encode($data['materials']);
        }

        DB::table('crafting_materials')->insert($craftingMaterials);
    }
}
