<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [

            // =========================
            // ❄️ Frost Revenant (Valdora Grassland)
            // =========================

            [
                'name' => 'Ice Crystal',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'A shard formed from condensed frost energy.',
                // Drops from: Frost Revenant
            ],
            [
                'name' => 'Frozen Core',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A rare crystallized core containing pure cold essence.',
                // Drops from: Frost Revenant
            ],

            // =========================
            // 🪓 Orc Warrior (Valdora Grassland)
            // =========================

            [
                'name' => 'Orc Axe',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'A crude but heavy axe used by Orc warriors.',
                // Drops from: Orc Warrior
            ],
            [
                'name' => 'War Badge',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'A token earned from fallen warriors.',
                // Drops from: Orc Warrior
            ],

            // =========================
            // 🐰 Rabitat (Valdora Grassland)
            // =========================

            [
                'name' => 'Rabbit Fur',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Soft fur collected from wild creatures.',
                // Drops from: Rabitat
            ],
            [
                'name' => 'Small Meat',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Basic meat from low-level beasts.',
                // Drops from: Rabitat
            ],

            // =========================
            // 🔥 Hellfire Behemoth (Dark Forest)
            // =========================

            [
                'name' => 'Hell Core',
                'type' => 'material',
                'rarity' => 'epic',
                'description' => 'A burning core filled with unstable fire energy.',
                // Drops from: Hellfire Behemoth
            ],
            [
                'name' => 'Burning Gem',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A gem infused with fire essence.',
                // Drops from: Hellfire Behemoth
            ],

            // =========================
            // 👹 Goblin Grunt (Dark Forest)
            // =========================

            [
                'name' => 'Goblin Ear',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Proof of a slain goblin.',
                // Drops from: Goblin Grunt
            ],
            [
                'name' => 'Rusty Shield',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'An old broken shield that can still be reforged.',
                // Drops from: Goblin Grunt
            ],

            // =========================
            // ⚡ Thunder Drake (Dark Forest)
            // =========================

            [
                'name' => 'Lightning Core',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A compressed energy core charged with lightning.',
                // Drops from: Thunder Drake
            ],
            [
                'name' => 'Dragon Scale',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'A hardened scale from a young dragon.',
                // Drops from: Thunder Drake
            ],

            // =========================
            // 🧟 Orc Zombie (Dark Forest)
            // =========================

            [
                'name' => 'Rot Flesh',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Decayed flesh from undead creatures.',
                // Drops from: Orc Zombie
            ],
            [
                'name' => 'Zombie Bone',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Bones infused with necrotic energy.',
                // Drops from: Orc Zombie
            ],

            // =========================
            // 🌑 Crystal Cave Monsters
            // =========================

            [
                'name' => 'Dark Cloth',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'A fabric that absorbs light.',
                // Drops from: Shadow Stalker
            ],
            [
                'name' => 'Shadow Dagger',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A dagger forged in shadow energy.',
                // Drops from: Shadow Stalker
            ],
            [
                'name' => 'Horn Fragment',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'Broken horn that still crackles with energy.',
                // Drops from: StormHorn Guardian
            ],
            [
                'name' => 'Storm Essence',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'Pure elemental lightning energy.',
                // Drops from: StormHorn Guardian
            ],
            [
                'name' => 'Lizard Scale',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Tough scale from desert reptiles.',
                // Drops from: High Lizard
            ],
            [
                'name' => 'Fire Sac',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'Heat organ that stores flame energy.',
                // Drops from: High Lizard
            ],
            [
                'name' => 'Boar Tusks',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Sharp tusks from wild boars.',
                // Drops from: Boar Wrath
            ],
            [
                'name' => 'Wild Meat',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Fresh meat from forest beasts.',
                // Drops from: Boar Wrath
            ],

            // =========================
            // 🌌 Sky Islands Monsters
            // =========================

            [
                'name' => 'Mutant Wool',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'Warped wool filled with unstable energy.',
                // Drops from: Mutant Sheep
            ],
            [
                'name' => 'Strange Meat',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Unknown meat with unnatural properties.',
                // Drops from: Mutant Sheep
            ],
            [
                'name' => 'Vampire Fang',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A cursed fang containing blood magic.',
                // Drops from: Blood Fang
            ],
            [
                'name' => 'Blood Crystal',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A crystal formed from condensed life essence.',
                // Drops from: Blood Fang
            ],
            [
                'name' => 'Burnt Meat',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Overcooked meat, barely usable.',
                // Drops from: Ember Hog
            ],
            [
                'name' => 'Ember Core',
                'type' => 'material',
                'rarity' => 'rare',
                'description' => 'A stabilized fire energy core.',
                // Drops from: Ember Hog
            ],
            [
                'name' => 'Ancient Scale',
                'type' => 'material',
                'rarity' => 'legendary',
                'description' => 'A scale from an ancient dragon.',
                // Drops from: Zyrath Scaleborn
            ],
            [
                'name' => 'Dragon Heart',
                'type' => 'material',
                'rarity' => 'mythic',
                'description' => 'The core essence of a dragon’s power.',
                // Drops from: Zyrath Scaleborn
            ],
            [
                'name' => 'Wolf Pelt',
                'type' => 'material',
                'rarity' => 'common',
                'description' => 'Thick fur used for cold resistance.',
                // Drops from: Dire Wolf
            ],
            [
                'name' => 'Sharp Fang',
                'type' => 'material',
                'rarity' => 'uncommon',
                'description' => 'A predator’s fang sharpened by instinct.',
                // Drops from: Dire Wolf
            ],
        ];

        foreach ($materials as &$material) {
            $material['created_at'] = now();
            $material['updated_at'] = now();
        }

        DB::table('materials')->insert($materials);
    }
}
