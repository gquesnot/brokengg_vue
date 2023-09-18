<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\LolMatch;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchesController extends Controller
{
    public function index(Request $request, Summoner $summoner)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        [$query, $encounter_query] = $summoner->getSummonerMatchQuery($filters);

        $match_ids = $query->pluck('match_id');
        $encounter_match_ids = $encounter_query->pluck('match_id');
        // fix order
        $matches = SummonerMatch::whereSummonerId($summoner->id)
            ->whereIn('summoner_matchs.match_id', $match_ids)
            ->orderByDesc(LolMatch::select('match_creation')->whereColumn('lol_matchs.id', 'summoner_matchs.match_id'))
            ->loadPartial()
            ->paginate(20);

        return Inertia::render('Summoner/Matches', [
            'summoner_stats' => $summoner->getSummonerStats($match_ids),
            'matches' => $matches,
            'summoner_encounter_count' => $summoner->getEncountersCount($encounter_match_ids),
        ]);
    }
}
