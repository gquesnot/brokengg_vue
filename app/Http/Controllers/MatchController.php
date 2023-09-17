<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\FilterHelper;
use App\Models\LolMatch;
use App\Models\Summoner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchController extends Controller
{
    public function index(Request $request, Summoner $summoner, LolMatch $match)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        $data = array_merge(
            ControllerHelper::getBaseInertiaResponse($summoner),
            [
                'match' => $match,
                "filters" => $filters_cpy,
            ]
        );
        return Inertia::render('Summoner/Match', $data);
    }
}
