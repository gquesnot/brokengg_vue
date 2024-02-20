<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiltersRequest;
use App\Traits\ControllerTrait;
use Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;

class ChampionsController extends Controller
{
    use ControllerTrait;

    public function index(FiltersRequest $request, int $summoner_id)
    {
        try {
            $summoner = $this->get_summoner($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        $filters = Arr::get($request->validated(), 'filters', []);
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $champions = $query->championsCalc()->with('champion')->paginate(20);

        return Inertia::render('Summoner/Champions', [
            'champions' => $champions,
            'summoner' => $summoner,
            'champion_options' => fn() => $this->get_champion_options(),
            'queue_options' => fn() => $this->get_queue_options($summoner),
            'filters' => $filters,
            'version' => fn() => $this->get_last_version(),
            'only' => fn() => ['champions'],

        ]);

    }
}
