<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            [
                'class' => 'Archer',
                'name' => 'Piercing Arrow',
                'description' => 'Shoots a powerful arrow that pierces enemies.',
                'target' => 2,
                'element' => 'fire',
                'requirement_level' => 1,
                'damage' => 20,
                'mana_cost' => 5,
                'icon_path' => URL::to('skills/Archer1.png'),
            ],
            [
                'class' => 'Archer',
                'name' => 'Rain of Arrows',
                'description' => 'Launches multiple arrows raining from the sky.',
                'target' => 3,
                'element' => 'water',
                'requirement_level' => 10,
                'damage' => 25,
                'mana_cost' => 20,
                'icon_path' => URL::to('skills/Archer2.png'),
            ],

            [
                'class' => 'Archer',
                'name' => 'Falcon Shot',
                'description' => 'A fast and accurate arrow strike.',
                'target' => 1,
                'element' => 'wind',
                'requirement_level' => 15,
                'damage' => 80,
                'mana_cost' => 20,
                'icon_path' => URL::to('skills/Archer3.png'),
            ],
            [
                'class' => 'Archer',
                'name' => 'Explosive Arrow',
                'description' => 'Arrow explodes on impact dealing splash damage.',
                'target' => 3,
                'element' => 'fire',
                'requirement_level' => 25,
                'damage' => 90,
                'mana_cost' => 30,
                'icon_path' => URL::to('skills/Archer4.png'),
            ],

            [
                'class' => 'Archer',
                'name' => 'Electra Dash',
                'description' => 'Enhances speed and next attack damage.',
                'target' => 2,
                'element' => 'electric',
                'requirement_level' => 30,
                'damage' => 140,
                'mana_cost' => 30,
                'icon_path' => URL::to('skills/Archer5.png'),
            ],


            /*
            |--------------------------------------------------------------------------
            | ASSASSIN
            |--------------------------------------------------------------------------
            */

            [
                'class' => 'Assassin',
                'name' => 'Shadow Strike',
                'description' => 'Quickly attacks from the shadows.',
                'target' => 2,
                'element' => 'fire',
                'requirement_level' => 1,
                'damage' => 20,
                'mana_cost' => 5,
                'icon_path' => URL::to('skills/Assassin1.png'),
            ],

            [
                'class' => 'Assassin',
                'name' => 'Electra Dagger',
                'description' => 'Throws a electric dagger causing damage over time.',
                'target' => 3,
                'element' => 'electric',
                'requirement_level' => 5,
                'damage' => 25,
                'mana_cost' => 20,
                'icon_path' => URL::to('skills/Assassin2.png'),
            ],

            [
                'class' => 'Assassin',
                'name' => 'Blade Dance',
                'description' => 'Rapidly slashes nearby enemies.',
                'target' => 1,
                'element' => 'water',
                'requirement_level' => 15,
                'damage' => 80,
                'mana_cost' => 20,
                'icon_path' => URL::to('skills/Assassin3.png'),
            ],

            [
                'class' => 'Assassin',
                'name' => 'Nightmare Slash',
                'description' => 'Dark slash infused with nightmare energy.',
                'target' => 2,
                'element' => 'wind',
                'requirement_level' => 25,
                'damage' => 120,
                'mana_cost' => 35,
                'icon_path' => URL::to('skills/Assassin4.png'),
            ],

            [
                'class' => 'Assassin',
                'name' => 'Smoke Veil',
                'description' => 'Covers the battlefield with smoke.',
                'target' => 3,
                'element' => 'earth',
                'requirement_level' => 30,
                'damage' => 110,
                'mana_cost' => 25,
                'icon_path' => URL::to('skills/Assassin5.png'),
            ],


            /*
            |--------------------------------------------------------------------------
            | KNIGHT
            |--------------------------------------------------------------------------
            */

            [
                'class' => 'Knight',
                'name' => 'Shield Bash',
                'description' => 'Smashes enemy using a heavy shield.',
                'target' => 2,
                'element' => 'water',
                'requirement_level' => 1,
                'damage' => 15,
                'mana_cost' => 5,
                'icon_path' => URL::to('skills/Knight1.png'),
            ],

            [
                'class' => 'Knight',
                'name' => 'Earth Breaker',
                'description' => 'Strikes the ground causing shockwaves.',
                'target' => 1,
                'element' => 'earth',
                'requirement_level' => 5,
                'damage' => 55,
                'mana_cost' => 20,
                'icon_path' => URL::to('skills/Knight2.png'),
            ],

            [
                'class' => 'Knight',
                'name' => 'Iron Will',
                'description' => 'Raises defense and durability.',
                'target' => 3,
                'element' => 'electric',
                'requirement_level' => 10,
                'damage' => 55,
                'mana_cost' => 25,
                'icon_path' => URL::to('skills/Knight3.png'),
            ],

            [
                'class' => 'Knight',
                'name' => 'Sword Tempest',
                'description' => 'Spins violently with sword attacks.',
                'target' => 1,
                'element' => 'water',
                'requirement_level' => 25,
                'damage' => 130,
                'mana_cost' => 30,
                'icon_path' => URL::to('skills/Knight4.png'),
            ],

            [
                'class' => 'Knight',
                'name' => 'Holy Guard',
                'description' => 'Blesses the knight with holy protection.',
                'target' => 3,
                'element' => 'fire',
                'requirement_level' => 30,
                'damage' => 110,
                'mana_cost' => 35,
                'icon_path' => URL::to('skills/Knight5.png'),
            ],


            /*
            |--------------------------------------------------------------------------
            | CRUSADER
            |--------------------------------------------------------------------------
            */

            [
                'class' => 'Crusader',
                'name' => 'Divine Smite',
                'description' => 'Calls divine power to smite enemies.',
                'target' => 2,
                'element' => 'wind',
                'requirement_level' => 1,
                'damage' => 15,
                'mana_cost' => 5,
                'icon_path' => URL::to('skills/Crusader1.png'),
            ],

            [
                'class' => 'Crusader',
                'name' => 'Sacred Flame',
                'description' => 'Holy flames burn surrounding enemies.',
                'target' => 1,
                'element' => 'fire',
                'requirement_level' => 5,
                'damage' => 60,
                'mana_cost' => 25,
                'icon_path' => URL::to('skills/Crusader2.png'),
            ],

            [
                'class' => 'Crusader',
                'name' => 'Judgement Blade',
                'description' => 'A powerful strike filled with justice.',
                'target' => 3,
                'element' => 'earth',
                'requirement_level' => 15,
                'damage' => 50,
                'mana_cost' => 25,
                'icon_path' => URL::to('skills/Crusader3.png'),
            ],

            [
                'class' => 'Crusader',
                'name' => 'Berserk Aura',
                'description' => 'Berserks divine aura.',
                'target' => 1,
                'element' => 'fire',
                'requirement_level' => 25,
                'damage' => 110,
                'mana_cost' => 25,
                'icon_path' => URL::to('skills/Crusader4.png'),
            ],

            [
                'class' => 'Crusader',
                'name' => 'Celestial Hammer',
                'description' => 'Drops a heavenly hammer upon enemies.',
                'target' => 2,
                'element' => 'water',
                'requirement_level' => 30,
                'damage' => 130,
                'mana_cost' => 35,
                'icon_path' => URL::to('skills/Crusader5.png'),
            ],


            /*
            |--------------------------------------------------------------------------
            | WIZARD
            |--------------------------------------------------------------------------
            */

            [
                'class' => 'Wizard',
                'name' => 'Fireball',
                'description' => 'Throws a massive fireball.',
                'target' => 2,
                'element' => 'fire',
                'requirement_level' => 1,
                'damage' => 25,
                'mana_cost' => 5,
                'icon_path' => URL::to('skills/Wizard1.png'),
            ],

            [
                'class' => 'Wizard',
                'name' => 'Frost Nova',
                'description' => 'Freezes nearby enemies.',
                'target' => 3,
                'element' => 'water',
                'requirement_level' => 5,
                'damage' => 40,
                'mana_cost' => 30,
                'icon_path' => URL::to('skills/Wizard2.png'),
            ],

            [
                'class' => 'Wizard',
                'name' => 'Thunderstorm',
                'description' => 'Summons lightning from the sky.',
                'target' => 3,
                'element' => 'electric',
                'requirement_level' => 15,
                'damage' => 60,
                'mana_cost' => 30,
                'icon_path' => URL::to('skills/Wizard3.png'),
            ],

            [
                'class' => 'Wizard',
                'name' => 'Arcane Blast',
                'description' => 'Unleashes condensed arcane energy.',
                'target' => 3,
                'element' => 'earth',
                'requirement_level' => 25,
                'damage' => 100,
                'mana_cost' => 45,
                'icon_path' => URL::to('skills/Wizard4.png'),
            ],

            [
                'class' => 'Wizard',
                'name' => 'Meteor Strike',
                'description' => 'Calls down a devastating meteor.',
                'target' => 1,
                'element' => 'fire',
                'requirement_level' => 30,
                'damage' => 150,
                'mana_cost' => 40,
                'icon_path' => URL::to('skills/Wizard5.png'),
            ],
        ];

        DB::table('skills')->insert($skills);
    }
}
