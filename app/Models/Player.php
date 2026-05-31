<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'class_type',
        'current_level',
        'current_experience',
        'max_health',
        'current_health',
        'max_mana',
        'current_mana',
        'current_gold',
        'current_diamond',
        'total_attack',
        'total_defense',
        'total_speed',
        'total_evasion_percentage',
        'total_critical_percentage',
        'current_map_id',
        'x',
        'y',
        'direction',
        'helm_id',
        'armor_id',
        'pants_id',
        'boots_id',
        'gloves_id',
        'weapon_id',
        'shield_id',
        'accessory_id',
        'is_online',
        'user_id',
        //new fields
        'active_buff_effects',
        'selected_talent_skills',

        'card_slot_1',
        'card_slot_2',
        'card_slot_3',
        'card_slot_4',

        'daily_bet_chance',
        'daily_trivia_chance',
        'daily_mobs_kill',
        'daily_fishing_chance',

        'activity_status',

        'in_pvp',
        'pvp_battle_id',

        'guild_id'
    ];

    protected $casts = [
        'active_buff_effects' => 'array',
        'selected_talent_skills' => 'array',
    ];

    protected $appends = [
        'is_exp_potion_active'
    ];

    const BASE_ATTACK = 10;
    const BASE_DEFENSE = 10;
    const BASE_HP = 100;
    const BASE_MP = 70;
    const BASE_SPEED = 5;
    const BASE_EVASION = 2;
    const BASE_CRIT = 2;
    const BASE_STUN = 1;

    const LEVEL_ATTACK_GAIN = 3;
    const LEVEL_DEFENSE_GAIN = 3;
    const LEVEL_HP_GAIN = 8;
    const LEVEL_MP_GAIN = 8;

    public function getBaseStats()
    {
        return match (strtolower($this->class_type)) {
            'wizard' => [
                'attack' => self::BASE_ATTACK - 2,
                'defense' => self::BASE_DEFENSE - 1,
                'hp' => self::BASE_HP - 15,
                'mp' => self::BASE_MP + 50,
                'speed' => self::BASE_SPEED,
                'evasion' => self::BASE_EVASION,
                'crit' => self::BASE_CRIT + 3,
                'stun' => self::BASE_STUN
            ],
            'assassin' => [
                'attack' => self::BASE_ATTACK + 3,
                'defense' => self::BASE_DEFENSE + 3,
                'hp' => self::BASE_HP + 40,
                'mp' => self::BASE_MP - 20,
                'speed' => self::BASE_SPEED - 1,
                'evasion' => self::BASE_EVASION,
                'crit' => self::BASE_CRIT + 5,
                'stun' => self::BASE_STUN
            ],
            'archer' => [
                'attack' => self::BASE_ATTACK + 2,
                'defense' => self::BASE_DEFENSE,
                'hp' => self::BASE_HP,
                'mp' => self::BASE_MP,
                'speed' => self::BASE_SPEED + 3,
                'evasion' => self::BASE_EVASION + 2,
                'crit' => self::BASE_CRIT + 2,
                'stun' => self::BASE_STUN
            ],
            'knight' => [
                'attack' => self::BASE_ATTACK + 2,
                'defense' => self::BASE_DEFENSE + 5,
                'hp' => self::BASE_HP + 30,
                'mp' => self::BASE_MP - 10,
                'speed' => self::BASE_SPEED + 2,
                'evasion' => self::BASE_EVASION - 2,
                'crit' => self::BASE_CRIT + 1,
                'stun' => self::BASE_STUN
            ],
            'crusader' => [
                'attack' => self::BASE_ATTACK + 5,
                'defense' => self::BASE_DEFENSE,
                'hp' => self::BASE_HP + 40,
                'mp' => self::BASE_MP - 10,
                'speed' => self::BASE_SPEED + 2,
                'evasion' => self::BASE_EVASION - 2,
                'crit' => self::BASE_CRIT + 2,
                'stun' => self::BASE_STUN
            ],
            default => [
                'attack' => self::BASE_ATTACK,
                'defense' => self::BASE_DEFENSE,
                'hp' => self::BASE_HP,
                'mp' => self::BASE_MP,
                'speed' => self::BASE_SPEED,
                'evasion' => self::BASE_EVASION,
                'crit' => self::BASE_CRIT,
                'stun' => self::BASE_STUN
            ],
        };
    }

    public function registerPlayer($name, $class_type, $user_id)
    {
        $this->name = $name;
        $this->class_type = $class_type;
        $base = $this->getBaseStats();

        $this->current_level = 1;
        $this->current_experience = 0;

        // HP / MP
        $this->max_health = $base['hp'];
        $this->current_health = $base['hp'];

        $this->max_mana = $base['mp'];
        $this->current_mana = $base['mp'];

        // ECONOMY
        $this->current_gold = 0;
        $this->current_diamond = 0;

        // COMBAT STATS
        $this->total_attack = $base['attack'];
        $this->total_defense = $base['defense'];
        $this->total_speed = $base['speed'];
        $this->total_evasion_percentage = $base['evasion'];
        $this->total_critical_percentage = $base['crit'];
        $this->total_stun_percentage = $base['stun'];

        // LOCATION
        $town = Map::where('name', 'Wisteria Town')->firstOrFail();

        $this->current_map_id = $town->map_id;
        $this->x = 10;
        $this->y = 7;
        $this->direction = 'down';

        $data = self::create($this->attributesToArray());

        User::where('id', $user_id)->update([
            'player_id' => $data->id,
            'role' => 'player'
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }


    public function getExpPercentage()
    {
        $requiredExp = Experience::where('level', $this->current_level)
            ->value('required_experience');

        if (!$requiredExp) {
            return 0;
        }

        return (int) (($this->current_experience / $requiredExp) * 100);
    }

    public function recalculateStats()
    {
        $player = self::with([
            'helmet.gear',
            'weapon.gear',
            'armor.gear',
            'boots.gear',
            'gloves.gear',
            'shield.gear',
            'necklace.gear',
            'ring.gear',
            'pants.gear',
            'wing.gear',
            'cardSlot1.card',
            'cardSlot2.card',
            'cardSlot3.card',
            'cardSlot4.card'
        ])->find($this->id);

        $stats = [
            'attack' => 0,
            'defense' => 0,
            'speed' => 0,
            'hp' => 0,
            'mp' => 0,
            'evasion' => 0,
            'crit' => 0,
            'stun' => 0,
        ];

        $gearSlots = [
            $player->helmet,
            $player->weapon,
            $player->armor,
            $player->boots,
            $player->gloves,
            $player->shield,
            $player->necklace,
            $player->ring,
            $player->pants,
            $player->wing,
        ];

        foreach ($gearSlots as $slot) {
            if (!$slot || !$slot->gear) continue;

            $gear = $slot->gear;

            $base = json_decode($gear->basic_stats ?? '{}', true);
            $random = json_decode($slot->random_stats ?? '{}', true);

            $multiplier = 1 + ((int)$slot->enhancement_level * 0.08);

            foreach ([$base, $random] as $statSet) {
                if (!$statSet) continue;

                foreach ($statSet as $key => $value) {
                    if (isset($stats[$key])) {
                        $stats[$key] += $value * $multiplier;
                    }
                }
            }
        }

        $cardStats = $this->getTotalCardStats();
        $baseStats = $this->getTotalBaseStats();

        $this->total_attack = $baseStats['attack'] + $stats['attack'] + $cardStats['attack'];
        $this->total_defense = $baseStats['defense'] + $stats['defense'] + $cardStats['defense'];
        $this->max_health = $baseStats['hp'] + $stats['hp'] + $cardStats['hp'];
        $this->max_mana = $baseStats['mp'] + $stats['mp'] + $cardStats['mp'];
        $this->total_speed = $baseStats['speed'] + $stats['speed'] + $cardStats['speed'];
        $this->total_evasion_percentage = $baseStats['evasion'] + $stats['evasion'] + $cardStats['evasion'];
        $this->total_critical_percentage = $baseStats['crit'] + $stats['crit'] + $cardStats['crit'];
        $this->total_stun_percentage = $baseStats['stun'] + $stats['stun'] + $cardStats['stun'];

        $this->save();
    }

    public function getTotalCardStats()
    {
        $player = self::with([
            'cardSlot1.card',
            'cardSlot2.card',
            'cardSlot3.card',
            'cardSlot4.card'
        ])->find($this->id);

        $stats = [
            'attack' => 0,
            'defense' => 0,
            'speed' => 0,
            'hp' => 0,
            'mp' => 0,
            'evasion' => 0,
            'crit' => 0,
            'stun' => 0,
        ];

        $cardSlots = [
            $player->cardSlot1,
            $player->cardSlot2,
            $player->cardSlot3,
            $player->cardSlot4,
        ];

        foreach ($cardSlots as $slot) {

            if (!$slot || !$slot->card) continue;

            $effects = json_decode($slot->card->effects ?? '[]', true);

            if (!is_array($effects)) continue;

            foreach ($effects as $effect) {

                $stat = $effect['stat'] ?? null;
                $value = $effect['value'] ?? 0;

                if (!$stat) continue;
                if (!isset($stats[$stat])) continue;

                $stats[$stat] += $value;
            }
        }

        return $stats;
    }

    public function getTotalBaseStats()
    {
        $level = $this->current_level;

        return [
            'attack' => self::BASE_ATTACK + ($level * self::LEVEL_ATTACK_GAIN),
            'defense' => self::BASE_DEFENSE + ($level * self::LEVEL_DEFENSE_GAIN),
            'hp' => self::BASE_HP + ($level * self::LEVEL_HP_GAIN),
            'mp' => self::BASE_MP + ($level * self::LEVEL_MP_GAIN),
            'speed' => self::BASE_SPEED,
            'evasion' => self::BASE_EVASION,
            'crit' => self::BASE_CRIT,
            'stun' => self::BASE_STUN
        ];
    }

    public function getActiveBuffs()
    {
        $now = now()->timestamp;

        return collect($this->active_buff_effects ?? [])
            ->filter(fn ($buff) => $buff['expires_at'] > $now)
            ->values();
    }

    public function getAllActiveTalents()
    {
        return collect($this->selected_talent_skills ?? [])
            ->flatMap(function ($talent) {
                // if it's still a JSON string (your current issue)
                if (is_string($talent)) {
                    return json_decode($talent, true) ?: [];
                }

                return $talent ?? [];
            })
            ->values();
    }

    public function cardSlot1()
    {
        return $this->belongsTo(Inventory::class, 'card_slot_1');
    }

    public function cardSlot2()
    {
        return $this->belongsTo(Inventory::class, 'card_slot_2');
    }

    public function cardSlot3()
    {
        return $this->belongsTo(Inventory::class, 'card_slot_3');
    }

    public function cardSlot4()
    {
        return $this->belongsTo(Inventory::class, 'card_slot_4');
    }

    public function getAllActiveEffects()
    {
        return array_merge(
            $this->getActiveBuffs()->toArray(),
            $this->getAllActiveTalents()->toArray()
        );
    }

    public function getAllStats()
    {
        $this->recalculateStats(); // gear + base only

        $stats = [
            'attack' => (int) $this->total_attack,
            'defense' => (int) $this->total_defense,
            'speed' => (int) $this->total_speed,
            'crit' => (int) $this->total_critical_percentage,
            'evasion' => (int) $this->total_evasion_percentage,
            'stun' => (int) $this->total_stun_percentage,
            'hp' => (int) $this->max_health,
            'mp' => (int) $this->max_mana,
            'is_exp_potion_active' => (bool) $this->is_exp_potion_active,
        ];
        foreach ($this->getAllActiveEffects() as $effect) {

            $stat = $effect['stat'];

            if (!isset($stats[$stat])) continue;

            $value = $effect['value'];

            $stats[$stat] = $effect['operation'] === 'multiply'
                ? $stats[$stat] * (1 + $value / 100)
                : $stats[$stat] + $value;
        }

        return $stats;
    }

    public function getIsExpPotionActiveAttribute(): bool
    {
        $now = now()->timestamp;

        $buffs = $this->active_buff_effects;

        $buffs = is_string($buffs)
            ? json_decode($buffs, true)
            : $buffs;

        $buffs = is_array($buffs) ? $buffs : [];

        foreach ($buffs as $buff) {
            if (($buff['stat'] ?? null) === 'exp' &&
                ($buff['expires_at'] ?? 0) > $now) {
                return true;
            }
        }

        return false;
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class, 'guild_id');
    }

    public function wing()
    {
        return $this->belongsTo(Inventory::class, 'wing_id');
    }

    public function helmet()
    {
        return $this->belongsTo(Inventory::class, 'helmet_id');
    }

    public function weapon()
    {
        return $this->belongsTo(Inventory::class, 'weapon_id');
    }

    public function armor()
    {
        return $this->belongsTo(Inventory::class, 'armor_id');
    }

    public function boots()
    {
        return $this->belongsTo(Inventory::class, 'boots_id');
    }

    public function gloves()
    {
        return $this->belongsTo(Inventory::class, 'gloves_id');
    }

    public function pants()
    {
        return $this->belongsTo(Inventory::class, 'pants_id');
    }

    public function shield()
    {
        return $this->belongsTo(Inventory::class, 'shield_id');
    }

    public function necklace()
    {
        return $this->belongsTo(Inventory::class, 'necklace_id');
    }

    public function ring()
    {
        return $this->belongsTo(Inventory::class, 'ring_id');
    }
}
