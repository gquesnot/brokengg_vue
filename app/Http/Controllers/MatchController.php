<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiltersRequest;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;

class MatchController extends Controller
{
    public function index(FiltersRequest $request, int $summoner_id, int $summoner_match_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
            $summoner_match = SummonerMatch::findOrFail($summoner_match_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        $filters = Arr::get($request->validated(), 'filters', []);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $encounter_match_ids = $encounter_query->pluck('match_id');

        return Inertia::render('Summoner/Match', [
            'summoner_match' => fn () => SummonerMatch::withPartial()->find($summoner_match->id),
            'summoner_encounter_count' => fn () => $summoner->get_encounters_count($encounter_match_ids),
        ]);
    }

    public function getSummonerMatchLoaded(Request $request, int $summoner_id, int $summoner_match_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
            $summoner_match = SummonerMatch::findOrFail($summoner_match_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }

        return response()->json([
            'summoner_match' => SummonerMatch::withAll()->find($summoner_match->id),
        ]);
    }

    public function getSummonerMatchDetail(Request $request, int $summoner_id, int $summoner_match_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
            $summoner_match = SummonerMatch::findOrFail($summoner_match_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        if (!$summoner_match->has_detail()) {

            try {
                $summoner->loadMatchDetail($summoner_match->match);
            } catch (ForbiddenException|NotFoundException $e) {

                return response()->json(['api' => 'Match not found'], 404);
            } catch (RateLimitReachedException $e) {
                return response()->json(['api' => 'Rate limit reached, please try again later'], 404);
            }

        }

        return response()->json([
            'match_participants_detail' => SummonerMatch::whereMatchId($summoner_match->match_id)
                ->withDetail()
                ->get(),
        ]);
    }
}
