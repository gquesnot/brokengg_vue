<?php

namespace App\Traits;

use App\Enums\FrameEventType;
use App\Events\SummonerUpdated;
use App\Models\Champion;
use App\Models\Item;
use App\Models\LolMatch;
use App\Models\Map;
use App\Models\Mode;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use App\Models\SummonerMatchFrame;
use App\Models\SummonerMatchFrameEvent;
use App\Models\SummonerMatchItem;
use App\Models\SummonerMatchPerk;
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
            $summoner_match_ids = SummonerMatch::whereMatchId($match->id)->pluck('id');
            SummonerMatchPerk::whereIn('summoner_match_id', $summoner_match_ids)->delete();
            SummonerMatchItem::whereIn('summoner_match_id', $summoner_match_ids)->delete();
            SummonerMatch::whereMatchId($match->id)->delete();
            $api_match = $this->getMatch($match->match_id);
            if (! $api_match || ! $this->updateMatchFromArray($match, $api_match)) {
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

            $perk_primary_selections = collect($participant['perks']['styles'][0]['selections'])->map(function ($perk) {
                return $perk['perk'];
            })->toArray();
            $perk_sub_selections = collect($participant['perks']['styles'][1]['selections'])->map(function ($perk) {
                return $perk['perk'];
            })->toArray();
            $perks = [
                'primary_style_id' => $participant['perks']['styles'][0]['style'],
                'primary_style1_id' => $perk_primary_selections[0],
                'primary_style2_id' => $perk_primary_selections[1],
                'primary_style3_id' => $perk_primary_selections[2],
                'sub_style_id' => $participant['perks']['styles'][1]['style'],
                'sub_style1_id' => $perk_sub_selections[0],
                'sub_style2_id' => $perk_sub_selections[1],
                'offense_id' => $participant['perks']['statPerks']['offense'],
                'flex_id' => $participant['perks']['statPerks']['flex'],
                'defense_id' => $participant['perks']['statPerks']['defense'],
            ];

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
                'wards_placed' => $participant['wardsPlaced'],
                'summoner_spell1_id' => $participant['summoner1Id'],
                'summoner_spell2_id' => $participant['summoner2Id'],
                'perks' => $perks,
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
                $perks,
            ];

        }
        foreach ($matches_to_add as [$summoner_match, $items, $perks]) {
            $summoner_match = SummonerMatch::create($summoner_match);
            foreach ($items as $item) {
                SummonerMatchItem::create(array_merge($item, [
                    'summoner_match_id' => $summoner_match->id,
                ]));
            }
            SummonerMatchPerk::create(array_merge($perks, [
                'summoner_match_id' => $summoner_match->id,
            ]));
        }

        $match->match_creation = Carbon::createFromTimestampMs($api_match['info']['gameCreation']);
        $match->match_end = Carbon::createFromTimestampMs($api_match['info']['gameEndTimestamp']);
        $match->match_duration = gmdate('H:i:s', $api_match['info']['gameDuration']);
        $match->map_id = $map->id;
        $match->queue_id = $queue->id;
        $match->mode_id = $mode->id;
        $match->updated = true;

        $match->save();
        SummonerUpdated::dispatch($this->id);

        return true;
    }

    public function loadMatchDetail(LolMatch $match)
    {
        $match_detail_array = $this->getMatchDetail($match->match_id);
        if (! $match_detail_array) {
            return null;
        }
        $participants = $match->participants()->with('summoner:id,puuid')->get();
        $summoner_match_ids = $participants->pluck('id')->toArray();
        $participant_ordered = [];
        foreach ($match_detail_array['metadata']['participants'] as $idx => $puuid) {
            $summoner_match = $participants->where('summoner.puuid', $puuid)->first();
            if ($summoner_match) {
                $participant_ordered[$idx + 1] = [
                    'summoner_match_id' => $summoner_match->id,
                    'summoner_match_frame_id' => null,

                ];
            }
        }
        $frames = $match_detail_array['info']['frames'];

        foreach ($frames as $frame) {
            $frames_to_add = [];
            $timestamp = Carbon::createFromTimestampMs($frame['timestamp']);
            foreach ($frame['participantFrames'] as $idx => $participant_frame) {
                $idx = intval($idx);
                $frames_to_add[] = [
                    'match_id' => $match->id,
                    'summoner_match_id' => $participant_ordered[$idx]['summoner_match_id'],
                    'current_timestamp' => $frame['timestamp'],
                    'champion_stats' => json_encode($participant_frame['championStats']),
                    'damage_stats' => json_encode($participant_frame['damageStats']),
                    'current_gold' => $participant_frame['currentGold'],
                    'gold_per_second' => $participant_frame['goldPerSecond'],
                    'jungle_minions_killed' => $participant_frame['jungleMinionsKilled'],
                    'level' => $participant_frame['level'],
                    'minions_killed' => $participant_frame['minionsKilled'],
                    'position_x' => $participant_frame['position']['x'],
                    'position_y' => $participant_frame['position']['y'],
                    'total_gold' => $participant_frame['totalGold'],
                    'xp' => $participant_frame['xp'],
                    'time_enemy_spent_controlled' => $participant_frame['timeEnemySpentControlled'],
                ];
            }
            SummonerMatchFrame::upsert($frames_to_add, ['match_id', 'summoner_match_id', 'current_timestamp'], ['champion_stats', 'damage_stats', 'current_gold', 'gold_per_second', 'jungle_minions_killed', 'level', 'minions_killed', 'position_x', 'position_y', 'total_gold', 'xp', 'time_enemy_spent_controlled']);
            $summoner_match_frames = SummonerMatchFrame::where('current_timestamp', $frame['timestamp'])
                ->whereIn('summoner_match_id', $summoner_match_ids)
                ->get();

            foreach ($participant_ordered as $idx => $participant) {
                $participant_ordered[$idx]['summoner_match_frame_id'] = $summoner_match_frames->where('summoner_match_id', $participant['summoner_match_id'])->first()->id;
            }

            $events_to_add = [];

            foreach ($frame['events'] as $event) {
                if (! isset($event['participantId'])) {
                    continue;
                }
                $type = FrameEventType::from($event['type']);
                $base = [
                    'current_timestamp' => $event['timestamp'],
                    'type' => $type,
                    'item_id' => Arr::get($event, 'itemId', null),
                    'before_id' => Arr::get($event, 'beforeId', null),
                    'after_id' => Arr::get($event, 'afterId', null),
                    'gold_gain' => Arr::get($event, 'goldGain', null),
                    'skill_slot' => Arr::get($event, 'skillSlot', null),
                    'level_up_type' => Arr::get($event, 'levelUpType', null),
                    'level' => Arr::get($event, 'level', null),
                    'position_x' => Arr::get($event, 'position.x', null),
                    'position_y' => Arr::get($event, 'position.y', null),
                    'summoner_match_victim_id' => null,
                    'summoner_match_frame_victim_id' => null,
                    'summoner_match_id' => null,
                    'summoner_match_frame_id' => null,
                ];

                if ($type == FrameEventType::CHAMPION_KILL) {
                    $base['summoner_match_id'] = $participant_ordered[$event['killerId']]['summoner_match_id'];
                    $base['summoner_match_frame_id'] = $participant_ordered[$event['killerId']]['summoner_match_frame_id'];
                    $base['summoner_match_victim_id'] = $participant_ordered[$event['victimId']]['summoner_match_id'];
                    $base['summoner_match_frame_victim_id'] = $participant_ordered[$event['victimId']]['summoner_match_frame_id'];
                } else {
                    $base['summoner_match_id'] = $participant_ordered[$event['participantId']]['summoner_match_id'];
                    $base['summoner_match_frame_id'] = $participant_ordered[$event['participantId']]['summoner_match_frame_id'];
                }
                $events_to_add[] = $base;
            }
            SummonerMatchFrameEvent::upsert($events_to_add, ['current_timestamp', 'type', 'summoner_match_id', 'summoner_match_frame_id'], ['summoner_match_victim_id', 'summoner_match_frame_victim_id', 'position_x', 'position_y', 'item_id', 'before_id', 'after_id', 'gold_gain', 'skill_slot', 'level_up_type', 'level']);

        }
    }

    public function getMatchDetail(string $match_id)
    {
        $url = "https://europe.api.riotgames.com/lol/match/v5/matches/{$match_id}/timeline";
        $response = Http::withoutVerifying()->withHeaders([
            'X-Riot-Token' => config('services.riot.api_key'),
        ])->get($url);
        $data = $response->json();
        if (self::responseLimitExceeded($data)) {
            sleep(20);

            return $this->getMatchDetail($match_id);
        }
        if (self::responseNotFound($data) || self::responseIsForbidden($data)) {
            return null;
        }

        return $data;
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
