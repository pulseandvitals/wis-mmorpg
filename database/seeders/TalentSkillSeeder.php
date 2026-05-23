<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TalentSkill;

class TalentSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $talents = [
            [
                'name' => 'True Strike',
                'description' => '+10% Attack damage',
                'effects' => json_encode([
                    [
                        'stat' => 'attack',
                        'operation' => 'multiply',
                        'value' => 10,
                        'value_type' => 'percent',
                    ]
                ]),
            ],

            [
                'name' => 'Iron Skin',
                'description' => '+12% Defense',
                'effects' => json_encode([
                    [
                        'stat' => 'defense',
                        'operation' => 'multiply',
                        'value' => 12,
                        'value_type' => 'percent',
                    ]
                ]),
            ],

            [
                'name' => 'Swift Step',
                'description' => '+15% Movement Speed',
                'effects' => json_encode([
                    [
                        'stat' => 'speed',
                        'operation' => 'multiply',
                        'value' => 15,
                        'value_type' => 'percent',
                    ]
                ]),
            ],

            [
                'name' => 'Critical Focus',
                'description' => '+8% Critical Rate',
                'effects' => json_encode([
                    [
                        'stat' => 'crit',
                        'operation' => 'multiply',
                        'value' => 8,
                        'value_type' => 'fixed',
                    ]
                ]),
            ],

            [
                'name' => 'Evasive Dance',
                'description' => '+8% Evasion Rate',
                'effects' => json_encode([
                    [
                        'stat' => 'evasion',
                        'operation' => 'multiply',
                        'value' => 8,
                        'value_type' => 'fixed',
                    ]
                ]),
            ],

            [
                'name' => 'Mana Flow',
                'description' => '+20% Mana increase',
                'effects' => json_encode([
                    [
                        'stat' => 'mp',
                        'operation' => 'multiply',
                        'value' => 20,
                        'value_type' => 'percent',
                    ]
                ]),
            ],

            [
                'name' => 'Health Boost',
                'description' => '+15% Health increase',
                'effects' => json_encode([
                    [
                        'stat' => 'hp',
                        'operation' => 'multiply',
                        'value' => 15,
                        'value_type' => 'percent',
                    ]
                ]),
            ],
            [
                'name' => 'Force Break',
                'description' => '+8% chance to stun on skills',
                'effects' => json_encode([
                    [
                        'stat' => 'stun',
                        'operation' => 'multiply',
                        'value' => 8,
                        'value_type' => 'fixed',
                    ]
                ]),
            ],
        ];

        foreach ($talents as $talent) {
            TalentSkill::create($talent);
        }
    }
}
