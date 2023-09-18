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
        [$query, $encounter_query] = $summoner->getSummonerMatchQuery($filters);
        $encounter_match_ids = $encounter_query->pluck('match_id');

        return Inertia::render('Summoner/Match', [
            'summoner_match' => fn () => SummonerMatch::loadPartial()->find($summoner_match->id),
            'summoner_encounter_count' => fn () => $summoner->getEncountersCount($encounter_match_ids),
        ]);
    }

    public function getSummonerMatchLoaded(Request $request, SummonerMatch $summoner_match)
    {
        return response()->json([
            'summoner_match' => SummonerMatch::loadAll()->find($summoner_match->id),
        ]);
    }
}
