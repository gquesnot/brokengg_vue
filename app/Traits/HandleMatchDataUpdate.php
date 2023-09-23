<?php

namespace App\Traits;

use App\Events\SummonerUpdated;
use App\Models\Champion;
use App\Models\Item;
use App\Models\LolMatch;
use App\Models\Map;
use App\Models\Mode;
use App\Models\Queue;
use App\Models\Summoner;
use App\Models\SummonerMatch;
use App\Models\SummonerMatchItem;
use App\Models\SummonerMatchPerk;
use Carbon\Carbon;

trait HandleMatchDataUpdate
{
    private function updateSummonerMatches(array $match_ids = null)
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

    private function updateMatchFromArray(LolMatch $match, array $api_match)
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
                'primary_selection_id' => $perk_primary_selections[0],
                'primary_selection1_id' => $perk_primary_selections[1],
                'primary_selection2_id' => $perk_primary_selections[2],
                'primary_selection3_id' => $perk_primary_selections[3],
                'sub_style_id' => $participant['perks']['styles'][1]['style'],
                'sub_selection1_id' => $perk_sub_selections[0],
                'sub_selection2_id' => $perk_sub_selections[1],
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
}
