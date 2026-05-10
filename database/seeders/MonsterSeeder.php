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
                'name' => 'Goblin Grunt',
                'description' => 'A fierce goblin that loves to fight.',
                'monster_type' => 'humanoid',
                'element' => 'earth',
                'attack' => json_encode(['name' => 'Club Bash', 'damage' => 5]),
                'gif_path' => URL::to('gifs/goblin_grunt.gif'),
            ],
            [
                'name' => 'Goblin Officer',
                'description' => 'A massive stone goblin with incredible strength.',
                'monster_type' => 'humanoid',
                'element' => 'earth',
                'attack' => json_encode(['name' => 'Crushing Boulder', 'damage' => 60]),
                'gif_path' => URL::to('gifs/stone_goliath.gif'),
            ],
            [
                'name' => 'Shadow Stalker',
                'description' => 'A stealthy assassin that strikes from the darkness.',
                'monster_type' => 'humanoid',
                'element' => 'shadow',
                'attack' => json_encode(['name' => 'Night Slash', 'damage' => 40]),
                'gif_path' => URL::to('gifs/shadow_stalker.gif'),
            ],
            [
                'name' => 'Frost Revenant',
                'description' => 'A chilling spirit that freezes its enemies.',
                'monster_type' => 'undead',
                'element' => 'ice',
                'attack' => json_encode(['name' => 'Glacial Pierce', 'damage' => 55]),
                'gif_path' => URL::to('gifs/frost_revenant.gif'),
            ],
            [
                'name' => 'Thunder Drake',
                'description' => 'A powerful dragon that harnesses the force of lightning.',
                'monster_type' => 'dragon',
                'attack' => json_encode(['name' => 'Storm Breath', 'damage' => 70]),
                'gif_path' => URL::to('gifs/thunder_drake.gif'),
            ],
        ];

        DB::table('monsters')->insert($monsters);
    }
}
