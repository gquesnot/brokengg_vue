<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
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
        # fix order
        $matches = SummonerMatch::whereSummonerId($summoner->id)
            ->whereIn('summoner_matchs.match_id', $match_ids)
            ->orderByDesc(LolMatch::select('match_creation')->whereColumn('lol_matchs.id', 'summoner_matchs.match_id'))
            ->with([
                'match:id,match_id,match_duration,match_end,mode_id,map_id,queue_id',
                'match.queue:id,description',
                'match.map:id,description',
                'match.mode:id,description',
                'items:id,img_url',
                'champion:id,name,img_url',
            ])
            ->with([
                'otherParticipants',
                'otherParticipants.summoner:id,name',
                'otherParticipants.champion:id,name,img_url',
            ])
            ->paginate(20);

        return Inertia::render('Summoner/Matches', [
            'summoner_stats' => $summoner->getSummonerStats($match_ids),
            'matches' => $matches,
            'summoner_encounter_count' => $summoner->getEncountersCount($encounter_match_ids),
        ]);
    }
}
