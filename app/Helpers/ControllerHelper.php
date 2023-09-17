<?php

namespace App\Helpers;

use App\Models\Champion;
use App\Models\LolMatch;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\Version;

class ControllerHelper
{
    static function getBaseInertiaResponse(Summoner $summoner)
    {

        return [
            'summoner' => fn() => $summoner,
            'version' => fn() => Version::orderByDesc('version')->first()?->version,
            'champion_options' => fn() => Champion::orderBy('name')
                ->get()
                ->map(fn(Champion $champion) => [
                    'value' => $champion->id,
                    'label' => $champion->name,
                ])->toArray(),
            'queue_options' => function () use ($summoner) {
                $match_ids = $summoner
                    ->summonerMatches()
                    ->pluck('match_id');
                $queue_ids = LolMatch::whereIn('id', $match_ids)
                    ->groupBy('queue_id')
                    ->pluck('queue_id')
                    ->filter(fn($id) => $id !== null);
                return Queue::whereIn('id', $queue_ids)
                    ->orderBy('description')
                    ->get()
                    ->map(fn($queue) => [
                        'value' => $queue->id,
                        'label' => $queue->description,
                    ])->toArray();
            }
        ];
    }
}
