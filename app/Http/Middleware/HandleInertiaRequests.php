<?php

namespace App\Http\Middleware;

use App\Http\Requests\FiltersRequest;
use App\Models\Champion;
use App\Models\LolMatch;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\Version;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request)
    {
        $filters = Validator::validate($request->all(), FiltersRequest::rules());
        $filters = Arr::get($filters, 'filters', []);

        $route_params = $request->route()->originalParameters();
        $summoner_id = Arr::get($route_params, 'summoner_id');

        $queue_options = [];
        $summoner = null;
        if ($summoner_id) {
            try {
                $summoner = Summoner::select(['id', 'name', 'profile_icon_id', 'summoner_level', 'tag_line'])->with('solo_q')->findOrFail($summoner_id);
            } catch (ModelNotFoundException $e) {
                return to_route('home');
            }

            $match_ids = $summoner
                ->summoner_matches()
                ->pluck('match_id');
            $queue_ids = LolMatch::whereIn('id', $match_ids)
                ->whereNotNull('queue_id')
                ->groupBy('queue_id')
                ->pluck('queue_id');
            $queue_options = Queue::whereIn('id', $queue_ids)
                ->orderBy('description')
                ->select(['id', 'description'])
                ->get()
                ->map(fn ($queue) => [
                    'value' => $queue->id,
                    'label' => $queue->description,
                ])->toArray();
        }

        $route_name = $request->route()->getName();
        $only = match ($route_name) {
            'summoner.matches' => ['summoner_stats', 'matches', 'summoner_encounter_count'],
            'summoner.match' => ['summoner_encounter_count'],
            'summoner.encounters' => ['encounters'],
            'summoner.encounter' => ['with_', 'vs_'],
            'summoner.champions' => ['champions'],
            'summoner.champion' => ['champion'],
            default => [],
        };

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'filters' => $filters,
            'champion_options' => function () {
                return Champion::select(['name', 'id'])->orderBy('name')
                    ->get()
                    ->map(fn (Champion $champion) => [
                        'value' => $champion->id,
                        'label' => $champion->name,
                    ])->toArray();
            },
            'queue_options' => $queue_options,
            'summoner' => $summoner,
            'version' => fn () => Version::orderByDesc('version')->first()->version,
            'route_params' => $route_params,
            'only' => $only,
        ]);
    }
}
