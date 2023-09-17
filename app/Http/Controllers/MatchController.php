<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\FilterHelper;
use App\Models\LolMatch;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchController extends Controller
{
    public function index(Request $request, Summoner $summoner, SummonerMatch $summoner_match)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        $this->fillMatch($summoner_match);
        return Inertia::render('Summoner/Match', [
            'summoner_match' => $summoner_match,
        ]);
    }

    public function fillMatch(&$summoner_match){
        $summoner_match->load([
            'match',
            'match.map',
            'match.queue',
            'match.mode',
            'match.participants',
            'match.participants.champion',
            'match.participants.summoner',
            'match.participants.items'
        ]);
    }


    public function getSummonerMatchLoaded(Request $request, SummonerMatch $summoner_match)
    {
        $this->fillMatch($summoner_match);
        return response()->json([
            'summoner_match' => $summoner_match
        ]);
    }
}
