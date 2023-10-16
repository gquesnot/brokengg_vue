<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Http\Requests\FiltersRequest;
use App\Models\Summoner;
use Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionsController extends Controller
{
    public function index(FiltersRequest $request, int $summoner_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        $filters = Arr::get($request->validated(), 'filters', []);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $champions = $query->championsCalc()->with('champion')->paginate(20);

        return Inertia::render('Summoner/Champions', [
            'champions' => $champions,
        ]);

    }
}
