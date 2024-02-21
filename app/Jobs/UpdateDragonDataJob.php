<?php

namespace App\Jobs;

use App\Models\Champion;
use App\Models\Item;
use App\Models\ItemMaps;
use App\Models\Map;
use App\Models\Mode;
use App\Models\Perk;
use App\Models\Queue;
use App\Models\SummonerSpell;
use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UpdateDragonDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $urls = [
        'versions' => 'https://ddragon.leagueoflegends.com/api/versions.json',
        'champions' => 'https://ddragon.leagueoflegends.com/cdn/{version}/data/en_US/champion.json',
        'items' => 'https://ddragon.leagueoflegends.com/cdn/{version}/data/en_US/item.json',
        'maps' => 'https://static.developer.riotgames.com/docs/lol/maps.json',
        'queues' => 'https://static.developer.riotgames.com/docs/lol/queues.json',
        'modes' => 'https://static.developer.riotgames.com/docs/lol/gameModes.json',
        'perks' => 'https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/perks.json',
        'summoner_spells' => 'https://ddragon.leagueoflegends.com/cdn/{version}/data/en_US/summoner.json',
        'runes_reforged' => 'https://ddragon.leagueoflegends.com/cdn/{version}/data/en_US/runesReforged.json',

    ];

    public function __construct()
    {

    }

    public function handle(): void
    {
        [$last_version, $is_new] = $this->updateVersion();
        if ($is_new) {
            $this->updateChampions($last_version);
            $this->updateMaps($last_version);
            $this->updateItems($last_version);

            $this->updateQueues($last_version);
            $this->updateModes($last_version);
            $this->updatePerks($last_version);
            $this->updateSummonerSpells($last_version);
        }

    }

    public function updateQueues($version)
    {
        $queues = Http::withoutVerifying()->get($this->urls['queues'])->json();
        foreach ($queues as $queue) {
            $queue_id = intval($queue['queueId']);
            $model = Queue::whereId($queue_id)->first();
            $description = $queue['description'];
            if (! $description) {
                $description = $queue['map'];
            }
            $data = [
                'description' => Str::replace(' games', '', $description),
                'id' => $queue_id,
            ];
            if ($model) {
                $model->update($data);
            } else {
                Queue::create($data);
            }
        }
    }

    public function updateMaps($version)
    {
        $maps = Http::withoutVerifying()->get($this->urls['maps'])->json();
        foreach ($maps as $map) {
            $map_id = intval($map['mapId']);
            $model = Map::whereId($map_id)->first();
            $data = [
                'description' => $map['mapName'],
                'id' => $map_id,
            ];
            if ($model) {
                $model->update($data);
            } else {
                Map::create($data);
            }
        }
    }

    public function updateChampions($version)
    {
        $champions = Http::withoutVerifying()->get(Str::replace('{version}', $version, $this->urls['champions']))->json();
        foreach ($champions['data'] as $champion_name => $champion) {
            $champion_id = intval($champion['key']);
            $model = Champion::whereId($champion_id)->first();
            $data = [
                'name' => $champion['name'],
                'id' => $champion_id,
                'title' => $champion['title'],
                'img_url' => $champion['image']['full'],
                'stats' => $champion['stats'],
            ];
            if ($model) {
                $model->update($data);
            } else {
                Champion::create($data);
            }
        }
    }

    public function updateItems($version)
    {
        $items = Http::withoutVerifying()->get(Str::replace('{version}', $version, $this->urls['items']))->json();
        foreach ($items['data'] as $item_id => $item) {
            $item_id = intval($item_id);
            $model = Item::whereId($item_id)->first();
            $data = [
                'name' => $item['name'],
                'id' => $item_id,
                'description' => $item['description'],
                'img_url' => $item['image']['full'],
                'tags' => $item['tags'],
                'stats' => $item['stats'],
            ];
            if ($model) {
                $model->update($data);
            } else {
                $model = Item::create($data);
            }
            $item_maps = collect(Arr::get($item, 'maps', []))
                ->filter(fn($has_map) => $has_map)
                ->map(function ($has_map, $map_id) use ($item_id) {
                    return ['map_id' => $map_id, 'item_id' => $item_id];
                })->toArray();
            ItemMaps::upsert($item_maps, ['map_id', 'item_id']);
        }
    }

    public function updateSummonerSpells($version)
    {
        $spells = Http::withoutVerifying()->get(Str::replace('{version}', $version, $this->urls['summoner_spells']))->json();
        foreach ($spells['data'] as $spell_name => $spell) {
            $spell_id = intval($spell['key']);
            $model = SummonerSpell::whereId($spell_id)->first();
            $data = [
                'name' => $spell['name'],
                'id' => $spell_id,
                'img_url' => $spell['image']['full'],
            ];
            if ($model) {
                $model->update($data);
            } else {
                SummonerSpell::create($data);
            }
        }
    }

    public function updateVersion()
    {
        $versions = Http::withoutVerifying()->get($this->urls['versions'])->json();
        $latestVersion = $versions[0];
        $latestDbVersion = Version::orderByDesc('version')->first()?->name;
        $is_new = $latestDbVersion === null || $latestDbVersion !== $latestVersion;
        if ($is_new) {
            Version::create(['version' => $latestVersion]);
        }

        return [$latestVersion, $is_new];
    }

    public function updateModes(mixed $last_version)
    {
        $modes = Http::withoutVerifying()->get($this->urls['modes'])->json();
        foreach ($modes as $mode) {
            $model = Mode::whereName($mode['gameMode'])->first();
            $data = [
                'name' => $mode['gameMode'],
                'description' => $mode['description'],
            ];
            if ($model) {
                $model->update($data);
            } else {
                Mode::create($data);
            }
        }
    }

    public function updatePerks(mixed $last_version)
    {
        $perks_raw_data = Http::withoutVerifying()->get($this->urls['perks'])->json();
        $perks_api_data = Http::withoutVerifying()->get(Str::replace('{version}', $last_version, $this->urls['runes_reforged']))->json();
        $perks_combined = [];

        foreach ($perks_raw_data as $perk) {
            $model = Perk::find($perk['id']);
            $perks_combined[] = [
                'id' => $perk['id'],
                'img_url' => Str::replace('/lol-game-data/assets/v1/perk-images/', '', $perk['iconPath']),
                'name' => $perk['name'],
            ];
        }
        foreach ($perks_api_data as $main_perk) {
            $perks_combined[] = [
                'id' => $main_perk['id'],
                'img_url' => Str::replace('perk-images/', '', $main_perk['icon']),
                'name' => $main_perk['name'],
            ];
        }
        foreach ($perks_combined as $data) {
            $model = Perk::find($data['id']);
            if ($model) {
                $model->update($data);
            } else {
                Perk::create($data);
            }
        }

    }
}
