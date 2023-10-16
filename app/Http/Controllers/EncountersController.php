<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Http\Requests\FiltersRequest;
use App\Models\Summoner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class EncountersController extends Controller
{
    public function index(FiltersRequest $request, int $summoner_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        $filters = Arr::get($request->validated(), 'filters', []);
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
