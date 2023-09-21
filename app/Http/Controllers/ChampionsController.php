<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\Summoner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionsController extends Controller
{
    public function index(Request $request, Summoner $summoner)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $champions = $query->championsCalc()->with('champion')->paginate(20);

        return Inertia::render('Summoner/Champions', [
            'champions' => $champions,
        ]);

    }
}
