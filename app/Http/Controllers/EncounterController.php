<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\LolMatch;
use App\Models\Summoner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EncounterController extends Controller
{
    public function index(Request $request, Summoner $summoner, Summoner $encounter)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $filtered_match_ids = $query->pluck('match_id')->toArray();

        return Inertia::render('Summoner/Encounter', [
            'encounter' => $encounter,
            'with_' => $this->getMatchData($summoner, $encounter, $filtered_match_ids, true),
            'vs_' => $this->getMatchData($summoner, $encounter, $filtered_match_ids, false),
        ]);
    }

    protected function getMatchData(Summoner $summoner, Summoner $encounter, array $filtered_match_ids, bool $with)
    {
        $match_ids = $this->getMatchIds($summoner, $encounter, $filtered_match_ids, $with);

        return [
            'matches' => $this->getMatches($summoner, $encounter, $match_ids),
            'summoner_stats' => $summoner->get_summoner_stats($match_ids),
            'encounter_stats' => $encounter->get_summoner_stats($match_ids),
        ];
    }

    protected function getMatchIds(Summoner $summoner, Summoner $encounter, array $filtered_match_ids, bool $with)
    {
        $operator = $with ? '=' : '!=';

        return $summoner->summoner_matches()
            ->whereIn('summoner_matchs.match_id', $filtered_match_ids)
            ->join('summoner_matchs as e', function ($join) use ($encounter) {
                $join->on('e.match_id', '=', 'summoner_matchs.match_id')
                    ->where('e.summoner_id', $encounter->id);
            })
            ->whereRaw("summoner_matchs.won {$operator} e.won")
            ->pluck('summoner_matchs.match_id');
    }

    protected function getMatches(Summoner $summoner, Summoner $encounter, $match_ids)
    {
        return LolMatch::whereIn('id', $match_ids)
            ->orderBy('match_creation', 'desc')
            ->with([
                'participants' => function ($query) use ($summoner, $encounter) {
                    $query->whereIn('summoner_id', [$summoner->id, $encounter->id]);
                },
                'participants.summoner:id,name',
                'participants.champion:id,name,img_url',
                'queue',
            ])->get();
    }
}
