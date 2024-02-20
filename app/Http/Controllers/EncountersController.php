<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiltersRequest;
use App\Traits\ControllerTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class EncountersController extends Controller
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
        $search = $request->input('search');
        [$query, $encounter_query] = $summoner->get_summoner_match_query($filters);
        $encounters_query = $summoner->get_encounters_count_query($query->pluck('match_id'))
            ->when($search != null && $search != '', function ($query) use ($search) {
                $query->where('summoners.name', 'like', '%'.$search.'%');
            });

        return Inertia::render('Summoner/Encounters', [
            'encounters' => $encounters_query->paginate(20),
            'search' => $search,
            'summoner' => $summoner,
            'champion_options' => fn() => $this->get_champion_options(),
            'queue_options' => fn() => $this->get_queue_options($summoner),
            'filters' => $filters,
            'version' => fn() => $this->get_last_version(),
            'only' => fn() => ['encounters'],
        ]);
    }
}
