<?php

namespace App\Traits;

use App\Models\Champion;
use App\Models\LolMatch;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\Version;
use Illuminate\Support\Str;

trait ControllerTrait
{
    public function get_champion_options()
    {
        return Champion::select(['name', 'id'])->orderBy('name')
            ->get()
            ->map(fn(Champion $champion) => [
                'value' => $champion->id,
                'label' => $champion->name,
            ])->toArray();
    }

    public function get_queue_options(Summoner $summoner)
    {
        $match_ids = $summoner
            ->summoner_matches()
            ->pluck('match_id');
        $queue_ids = LolMatch::whereIn('id', $match_ids)
            ->whereNotNull('queue_id')
            ->groupBy('queue_id')
            ->pluck('queue_id');

        return Queue::whereIn('id', $queue_ids)
            ->orderBy('description')
            ->select(['id', 'description'])
            ->get()
            ->map(fn($queue) => [
                'value' => $queue->id,
                'label' => Str::replace(' games', '', $queue->description),
            ])->toArray();
    }

    public function get_last_version()
    {
        return Version::orderByDesc('version')->first()->version;
    }

    public function get_summoner($summoner_id)
    {
        return Summoner::select(['id', 'name', 'profile_icon_id', 'summoner_level'])->with(['solo_q', 'pro_player'])->findOrFail($summoner_id);
    }
}
