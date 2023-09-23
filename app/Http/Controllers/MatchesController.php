<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\LolMatch;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchesController extends Controller
{
    public function index(Request $request, int $summoner_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);

        $match_ids = $query->pluck('match_id');
        $encounter_match_ids = $encounter_query->pluck('match_id');
        // fix order
        $matches = SummonerMatch::whereSummonerId($summoner->id)
            ->whereIn('summoner_matchs.match_id', $match_ids)
            ->orderByDesc(LolMatch::select('match_creation')->whereColumn('lol_matchs.id', 'summoner_matchs.match_id'))
            ->withPartial()
            ->paginate(20);

        return Inertia::render('Summoner/Matches', [
            'summoner_stats' => $summoner->get_summoner_stats($match_ids),
            'matches' => $matches,
            'summoner_encounter_count' => $summoner->get_encounters_count($encounter_match_ids),
        ]);
    }
}
