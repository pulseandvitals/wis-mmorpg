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
