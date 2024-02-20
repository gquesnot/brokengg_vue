<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiltersRequest;
use App\Models\Champion;
use App\Traits\ControllerTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class ChampionController extends Controller
{
    use ControllerTrait;

    public function index(FiltersRequest $request, int $summoner_id, int $champion_id)
    {
        try {
            $champion = Champion::findOrFail($champion_id);
            $summoner = $this->get_summoner($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }

        $filters = Arr::get($request->validated(), 'filters', []);

        return Inertia::render('Summoner/Champion', [
            'champion' => $champion,
            'summoner' => $summoner,
            'champion_options' => fn() => $this->get_champion_options(),
            'queue_options' => fn() => $this->get_queue_options($summoner),
            'version' => fn() => $this->get_last_version(),
            'filters' => $filters,
            'only' => fn() => ['champion'],
        ]);
    }
}
