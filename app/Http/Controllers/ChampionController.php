<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\Champion;
use App\Models\Summoner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionController extends Controller
{
    public function index(Request $request, Summoner $summoner, Champion $champion)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);

        return Inertia::render('Summoner/Champion', [
            'champion' => $champion,
        ]);
    }
}
