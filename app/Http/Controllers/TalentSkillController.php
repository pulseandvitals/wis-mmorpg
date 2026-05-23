<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\TalentSkill;
use Illuminate\Http\Request;

class TalentSkillController extends Controller
{
    public function getTalentSkills()
    {
        $talentSkills = TalentSkill::all();
        return response()->json($talentSkills);
    }

    public function resetTalents(Request $request)
    {
        $player = auth()->user()->player;

        if($player->current_diamond < $request->cost) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not enough diamonds to reset talents'
            ], 400);
        }

        $player->current_diamond -= $request->cost;
        $player->selected_talent_skills = null;
        $player->save();

        return response()->json([
            'message' => 'Talents reset successfully.',
            'player' => new PlayerResource($player),
        ]);
    }


    public function storeSelectedTalents(Request $request)
    {
        $request->validate([
            'talents' => 'required|array|max:3',
            'talents.*' => 'exists:talent_skills,id',
        ]);

        $player = auth()->user()->player;
        $effects = TalentSkill::whereIn('id', $request->talents)->pluck('effects')->toArray();

        $player->selected_talent_skills = $effects;
        $player->save();

        return response()->json([
                'message' => 'Selected talents saved successfully.',
                'player' => $player,
            ]);
    }
}
