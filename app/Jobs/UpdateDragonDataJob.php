<?php

namespace App\Jobs;

use App\Models\Champion;
use App\Models\Item;
use App\Models\Map;
use App\Models\Mode;
use App\Models\Queue;
use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use RiotAPI\DataDragonAPI\DataDragonAPI;

class UpdateDragonDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

    public function handle(): void
    {
        [$last_version, $is_new] = $this->updateVersion();
        if ($is_new) {
            $this->updateChampions($last_version);
            $this->updateItems($last_version);
            $this->updateMaps($last_version);
            $this->updateQueues($last_version);
            $this->updateModes($last_version);
        }

    }

    public function updateQueues($version)
    {
        $queues = Http::withoutVerifying()->get('https://static.developer.riotgames.com/docs/lol/queues.json')->json();
        foreach ($queues as $queue) {
            $queue_id = intval($queue['queueId']);
            $model = Queue::whereId($queue_id)->first();
            $description = $queue['description'];
            if (! $description) {
                $description = $queue['map'];
            }
            $data = [
                'description' => $description,
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
        $maps = DataDragonAPI::getStaticMaps(version: $version);
        foreach ($maps['data'] as $map_id => $map) {
            $map_id = intval($map['MapId']);
            $model = Map::whereId($map_id)->first();
            $data = [
                'description' => $map['MapName'],
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
        $champions = DataDragonAPI::getStaticChampions(version: $version);
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
        $items = DataDragonAPI::getStaticItems(version: $version);
        foreach ($items['data'] as $item_id => $item) {
            $item_id = intval($item_id);
            $model = Item::whereId($item_id)->first();
            $data = [
                'name' => $item['name'],
                'id' => $item_id,
                'description' => $item['description'],
                'img_url' => $item['image']['full'],
                'tags' => $item['tags'],
            ];
            if ($model) {
                $model->update($data);
            } else {
                Item::create($data);
            }
        }
    }

    public function updateVersion()
    {
        $versions = DataDragonAPI::getStaticVersions();
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
        $modes = Http::withoutVerifying()->get('https://static.developer.riotgames.com/docs/lol/gameModes.json')->json();
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
}
