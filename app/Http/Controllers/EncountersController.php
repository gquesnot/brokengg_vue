<?php

namespace App\Http\Controllers;

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
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $encounters_query = $summoner->get_encounters_count_query($query->pluck('match_id'))
            ->when($search != null && $search != '', function ($query) use ($search) {
                $query->where('summoners.name', 'like', '%'.$search.'%');
            });

        return Inertia::render('Summoner/Encounters', [
            'encounters' => $encounters_query->paginate(20),
            'search' => $search,
        ]);
    }
}
