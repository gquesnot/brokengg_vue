<?php

namespace App\Http\Middleware;

use App\Models\Champion;
use App\Models\LolMatch;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        $filters_data = $request->validate([
            'filters.champion_id' => 'nullable|integer',
            'filters.queue_id' => 'nullable|integer',
            'filters.start_date' => 'nullable|date',
            'filters.end_date' => 'nullable|date',
            'filters.should_filter_encounters' => 'nullable|boolean',
        ], [
            'filters.champion_id.string' => 'The selected champion is invalid.',
            'filters.queue_id.string' => 'The selected queue is invalid.',
            'filters.start_date.string' => 'The start date is not a valid date.',
            'filters.end_date.string' => 'The end date is not a valid date.',
            'filters.should_filter_encounters.boolean' => 'The should filter encounters field must be true or false.',
        ]);
        $new_filters = [
            'champion_id' => null,
            'queue_id' => null,
            'start_date' => null,
            'end_date' => null,
            'should_filter_encounters' => false,
        ];
        if ($filters_data) {
            foreach ($filters_data['filters'] as $key => $value) {
                if (in_array($key, ['champion_id', 'queue_id'])) {
                    $new_filters[$key] = intval($value);
                } else {
                    $new_filters[$key] = $value;
                }
            }
        }

        $route_params = $request->route()->originalParameters();
        $summoner_id = Arr::get($route_params, 'summoner');

        $champion_options = [];
        $queue_options = [];
        $summoner = null;
        if ($summoner_id) {
            $summoner = Summoner::find($summoner_id);
            $champion_options = Champion::orderBy('name')
                ->get()
                ->map(fn (Champion $champion) => [
                    'value' => $champion->id,
                    'label' => $champion->name,
                ])->toArray();
            $match_ids = $summoner
                ->summonerMatches()
                ->pluck('match_id');
            $queue_ids = LolMatch::whereIn('id', $match_ids)
                ->groupBy('queue_id')
                ->pluck('queue_id')
                ->filter(fn ($id) => $id !== null);
            $queue_options = Queue::whereIn('id', $queue_ids)
                ->orderBy('description')
                ->get()
                ->map(fn ($queue) => [
                    'value' => $queue->id,
                    'label' => $queue->description,
                ])->toArray();
            $summoner = $summoner->only([
                'id',
                'name',
                'profile_icon_id',
            ]);
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
            'filters' => fn () => $new_filters,
            'champion_options' => fn () => $champion_options,
            'queue_options' => fn () => $queue_options,
            'summoner' => fn () => $summoner,
            'version' => fn () => Version::orderByDesc('version')->first()->version,
            'route_params' => fn () => $route_params,
            'only' => fn () => $only,
        ]);
    }
}
