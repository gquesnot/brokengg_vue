<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\FilterHelper;
use App\Models\Summoner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EncountersController extends Controller
{
    public function index(Request $request, Summoner $summoner)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        $search = $request->input('search');
        [$query, $encounter_query] = $summoner->getSummonerMatchQuery($filters);
        $encounters_query = $summoner->getEncountersCountQuery($query->pluck('match_id'))
            ->when($search != null && $search != '', function ($query) use ($search) {
                $query->where('summoners.name', 'like', '%' . $search . '%');
            });
        $data = array_merge(
            ControllerHelper::getBaseInertiaResponse($summoner),
            [
                "filters" => $filters_cpy,
                'encounters' => $encounters_query->paginate(20),
                'search' => $search,
            ]
        );
        return Inertia::render('Summoner/Encounters', $data);
    }
}
