<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiltersRequest;
use App\Models\LolMatch;
use App\Models\Summoner;
use App\Traits\ControllerTrait;
use Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;

class EncounterController extends Controller
{
    use ControllerTrait;

    public function index(FiltersRequest $request, int $summoner_id, int $encounter_id)
    {
        try {
            $summoner = $this->get_summoner($summoner_id);
            $encounter = $this->get_summoner($encounter_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        $filters = Arr::get($request->validated(), 'filters', []);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $filtered_match_ids = $query->pluck('match_id')->toArray();

        return Inertia::render('Summoner/Encounter', [
            'encounter' => $encounter,
            'with_' => $this->getMatchData($summoner, $encounter, $filtered_match_ids, true),
            'vs_' => $this->getMatchData($summoner, $encounter, $filtered_match_ids, false),
            'summoner' => $summoner,
            'champion_options' => fn() => $this->get_champion_options(),
            'queue_options' => fn() => $this->get_queue_options($summoner),
            'filters' => $filters,
            'version' => fn() => $this->get_last_version(),
            'only' => fn() => ['encounter', 'with_', 'vs_'],
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
