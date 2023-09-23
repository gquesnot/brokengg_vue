<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchController extends Controller
{
    public function index(Request $request, Summoner $summoner, SummonerMatch $summoner_match)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $encounter_match_ids = $encounter_query->pluck('match_id');

        return Inertia::render('Summoner/Match', [
            'summoner_match' => fn () => SummonerMatch::withPartial()->find($summoner_match->id),
            'summoner_encounter_count' => fn () => $summoner->get_encounters_count($encounter_match_ids),
        ]);
    }

    public function getSummonerMatchLoaded(Request $request, Summoner $summoner, SummonerMatch $summoner_match)
    {
        return response()->json([
            'summoner_match' => SummonerMatch::withAll()->find($summoner_match->id),
        ]);
    }

    public function getSummonerMatchDetail(Request $request, Summoner $summoner, SummonerMatch $summoner_match)
    {

        if (!$summoner_match->has_detail()) {
            $summoner->loadMatchDetail($summoner_match->match);
        }

        return response()->json([
            'match_participants_detail' => SummonerMatch::whereMatchId($summoner_match->match_id)
                ->withDetail()
                ->get()
        ]);
    }
}
