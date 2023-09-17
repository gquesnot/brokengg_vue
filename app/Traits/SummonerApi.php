<?php

namespace App\Traits;

use App\Models\Champion;
use App\Models\Item;
use App\Models\ItemSummonerMatch;
use App\Models\LolMatch;
use App\Models\Map;
use App\Models\Mode;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

trait SummonerApi
{
    public static function updateOrCreateSummonerByName(string $summoner_name): ?Summoner
    {

        $summoner_array = Summoner::getSummonerByName($summoner_name);
        if (! $summoner_array) {
            return null;
        }
        if (Summoner::wherePuuid($summoner_array['puuid'])->exists()) {
            $summoner = Summoner::wherePuuid($summoner_array['puuid'])->first();
        } else {
            $summoner = new Summoner();
        }
        $summoner->updateSummonerWithArray($summoner_array);

        return $summoner;
    }

    public function updateSummonerByPuuid(): void
    {
        $summoner_array = $this->getSummonerByPUUID();
        if (! $summoner_array) {
            return;
        }
        $this->updateSummonerWithArray($summoner_array);
    }

    public static function getSummonerByName($summoner_name)
    {
        $url = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/{$summoner_name}";
        $response = Http::withoutVerifying()->withHeaders([
            'X-Riot-Token' => config('services.riot.api_key'),
        ])->get($url);
        $data = $response->json();
        if (self::responseLimitExceeded($data)) {
            sleep(20);

            return Summoner::getSummonerByName($summoner_name);
        }
        if (self::responseNotFound($data) || self::responseIsForbidden($data)) {
            return null;
        }

        return $data;
    }

    public function getSummonerByPuuid()
    {
        $url = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/{$this->puuid}";
        $response = Http::withoutVerifying()->withHeaders([
            'X-Riot-Token' => config('services.riot.api_key'),
        ])->get($url);
        $data = $response->json();
        if (self::responseLimitExceeded($data)) {
            sleep(20);

            return $this->getSummonerByPuuid();
        }
        if (self::responseNotFound($data) || self::responseIsForbidden($data)) {
            return null;
        }

        return $data;
    }

    public function updateSummonerWithArray(array $summoner_array)
    {
        $this->name = $summoner_array['name'];
        $this->puuid = $summoner_array['puuid'];
        $this->summoner_level = $summoner_array['summonerLevel'];
        $this->profile_icon_id = $summoner_array['profileIconId'];
        $this->revision_date = $summoner_array['revisionDate'];
        $this->account_id = $summoner_array['accountId'];
        $this->summoner_id = $summoner_array['id'];
        $this->save();
    }

    public function updateMatches()
    {
        $match_ids = $this->updateSummonerMatchIds();
        $this->updateSummonerMatches();
    }

    public function updateSummonerMatchIds(): array
    {
        $match_ids = $this->getAllMatchIds();
        $db_match_ids = collect($match_ids)
            ->map(function ($match_id) {
                return [
                    'match_id' => $match_id,
                ];
            })
            ->toArray();
        LolMatch::upsert($db_match_ids, ['match_id'], ['match_id']);

        return $match_ids;
    }

    public function getAllMatchIds(): array
    {
        $all_match_ids = [];
        foreach ($this->yieldMatchIds() as $match_id) {
            $all_match_ids[] = $match_id;
        }

        return $all_match_ids;
    }

    public function yieldMatchIds(): iterable
    {
        $start = 0;
        $count = 100;
        $max_count = config('services.riot.max_match_count') != 0 ? config('services.riot.max_match_count') : null;
        if ($max_count && $max_count < $count) {
            $count = $max_count;
        }
        while ($max_count == null || $start < $max_count) {
            $match_ids = $this->getSummonerMatchIds($start, $count);
            if (count($match_ids) > 0) {
                yield from $match_ids;
            } else {
                break;
            }
            $start += $count;
        }
    }

    public function getSummonerMatchIds($start = 0, $count = 100)
    {
        $url = "https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/{$this->puuid}/ids";
        $response = Http::withoutVerifying()->withHeaders([
            'X-Riot-Token' => config('services.riot.api_key'),
        ])->get($url, [
            'start' => $start,
            'count' => $count,
        ]);
        $data = $response->json();
        if (self::responseLimitExceeded($data)) {
            sleep(20);

            return $this->getSummonerMatchIds($start, $count);
        }
        if (self::responseNotFound($data) || self::responseIsForbidden($data)) {
            return null;
        }

        return $data;
    }

    public function updateSummonerMatches(array $match_ids = null)
    {
        $query = LolMatch::whereUpdated(false)->whereIsTrashed(false);
        if ($match_ids === null) {
            $matches = $query->get();
        } else {
            $matches = $query->whereIn('match_id', $match_ids)->get();
        }
        foreach ($matches as $match) {
            SummonerMatch::whereMatchId($match->match_id)->with('items')->get()->each(function ($summoner_match) {
                $summoner_match->items()->delete();
                $summoner_match->delete();
            });

            $api_match = $this->getMatch($match->match_id);
            if (! $api_match) {
                $match->update(['is_trashed' => true, 'updated' => true]);
            }
            if (! $this->updateMatchFromArray($match, $api_match)) {
                $match->update(['is_trashed' => true, 'updated' => true]);
            }
        }
    }

    public function updateMatchFromArray(LolMatch $match, array $api_match)
    {
        $map_id = intval($api_match['info']['mapId']);
        $queue_id = intval($api_match['info']['queueId']);

        $map = Map::find($map_id);
        $queue = Queue::find($queue_id);
        $mode = Mode::whereName($api_match['info']['gameMode'])->first();

        if (! $map || ! $queue || ! $mode) {
            return false;
        }
        $matches_to_add = [];
        foreach ($api_match['info']['participants'] as $participant) {
            // remake
            $champion_id = intval($participant['championId']);
            if ($participant['gameEndedInEarlySurrender']) {
                return false;
            }
            $summoner = Summoner::wherePuuid($participant['puuid'])->first();
            if (! $summoner) {
                $summoner = Summoner::create([
                    'name' => $participant['summonerName'],
                    'puuid' => $participant['puuid'],
                    'summoner_level' => $participant['summonerLevel'],
                    'profile_icon_id' => $participant['profileIcon'],
                    'summoner_id' => $participant['summonerId'],
                ]);
            }

            $champion = Champion::find($champion_id);
            $save_data = [
                'match_id' => $match->id,
                'summoner_id' => $summoner->id,
                'champion_id' => $champion->id,
                'champ_level' => $participant['champLevel'],
                'kills' => $participant['kills'],
                'deaths' => $participant['deaths'],
                'assists' => $participant['assists'],
                'won' => $participant['win'],
                'minions_killed' => $participant['totalMinionsKilled'],
                'largest_killing_spree' => $participant['largestKillingSpree'],
                'double_kills' => $participant['doubleKills'],
                'triple_kills' => $participant['tripleKills'],
                'quadra_kills' => $participant['quadraKills'],
                'penta_kills' => $participant['pentaKills'],
                'total_damage_dealt_to_champions' => $participant['totalDamageDealtToChampions'],
                'gold_earned' => $participant['goldEarned'],
                'total_damage_taken' => $participant['totalDamageTaken'],
            ];

            if (array_key_exists('challenges', $participant)) {
                if (array_key_exists('killParticipation', $participant['challenges'])) {
                    $save_data['kill_participation'] = $participant['challenges']['killParticipation'];
                }
                if (array_key_exists('kda', $participant['challenges'])) {
                    $save_data['kda'] = $participant['challenges']['kda'];
                }
            }
            //$summoner_match = SummonerMatch::create($data);
            $save_items = [];
            $items = [
                $participant['item0'],
                $participant['item1'],
                $participant['item2'],
                $participant['item3'],
                $participant['item4'],
                $participant['item5'],
                //$participant['item6'],
            ];
            foreach ($items as $idx => $item) {
                if ($item) {
                    $db_item = Item::find(intval($item));
                    if ($db_item) {
                        $save_items[] = [
                            'item_id' => $db_item->id,
                            'position' => $idx,
                        ];
                    }
                }
            }
            $matches_to_add[] = [
                $save_data,
                $save_items,
            ];

        }
        foreach ($matches_to_add as [$summoner_match, $items]) {
            $summoner_match = SummonerMatch::create($summoner_match);

            foreach ($items as $item) {
                ItemSummonerMatch::create(array_merge($item, [
                    'summoner_match_id' => $summoner_match->id,
                ]));
            }
        }

        $match->match_creation = Carbon::createFromTimestampMs($api_match['info']['gameCreation']);
        $match->match_end = Carbon::createFromTimestampMs($api_match['info']['gameEndTimestamp']);
        $match->match_duration = gmdate('H:i:s', $api_match['info']['gameDuration']);
        $match->map_id = $map->id;
        $match->queue_id = $queue->id;
        $match->mode_id = $mode->id;
        $match->updated = true;

        $match->save();

        return true;
    }

    public function getMatch(string $match_id)
    {
        $url = "https://europe.api.riotgames.com/lol/match/v5/matches/{$match_id}";
        $response = Http::withoutVerifying()->withHeaders([
            'X-Riot-Token' => config('services.riot.api_key'),
        ])->get($url);
        $data = $response->json();
        if (self::responseLimitExceeded($data)) {
            sleep(20);

            return $this->getMatch($match_id);
        }
        if (self::responseNotFound($data) || self::responseIsForbidden($data)) {
            return null;
        }

        return $data;
    }

    public function getLiveGame()
    {
        $url = "https://euw1.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/{$this->summoner_id}";
        $response = Http::withoutVerifying()->withHeaders([
            'X-Riot-Token' => config('services.riot.api_key'),
        ])->get($url);
        $data = $response->json();
        if (self::responseLimitExceeded($data)) {
            sleep(20);

            return $this->getLiveGame();
        }
        if (self::responseNotFound($data) || self::responseIsForbidden($data)) {
            return null;
        }

        return $data;
    }

    public static function responseLimitExceeded($data): bool
    {
        return Arr::get($data, 'status.status_code') == 429;
    }

    public static function responseNotFound($data)
    {
        return Arr::get($data, 'status.status_code') == 404;
    }

    public static function responseIsForbidden($data)
    {
        return Arr::get($data, 'status.status_code') == 403;
    }
}
