<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\FilterHelper;
use App\Models\Summoner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionsController extends Controller
{


    public function index(Request $request, Summoner $summoner)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        [$query, $encounter_query] = $summoner->getSummonerMatchQuery($filters);
        $champions = $query->championsCalc()->with('champion')->paginate(20);
        $data = array_merge(
            ControllerHelper::getBaseInertiaResponse($summoner),
            [
                'champions' => $champions,
                "filters" => $filters_cpy
            ]
        );
        return Inertia::render('Summoner/Champions', $data);

    }
}
