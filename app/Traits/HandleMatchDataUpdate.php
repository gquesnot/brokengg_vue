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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;

trait HandleMatchDataUpdate
{
    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function updateSummonerMatches(?array $match_ids = null): void
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
        $match_id = $match->match_id;
        //Storage::disk('local')->put("{$match_id}.json", json_encode($api_match));
        $map_id = intval($api_match['info']['mapId']);
        $queue_id = intval($api_match['info']['queueId']);

        $map = Map::find($map_id);
        $queue = Queue::find($queue_id);
        $mode = Mode::whereName($api_match['info']['gameMode'])->first();

        if (! $map || ! $queue || ! $mode) {
            return false;
        }

        $teams_kills = [
            100 => 0,
            200 => 0,
        ];
        foreach ($api_match['info']['teams'] as $team) {
            $teams_kills[$team['teamId']] = $team['objectives']['champion']['kills'];
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
                $summoner_name = Arr::get($participant, 'riotIdGameName');
                $summoner_tagline = Arr::get($participant, 'riotIdTagline');
                if (!$summoner_name || !$summoner_tagline) {
                    $account_detail = Summoner::getAccountByPuuid($participant['puuid']);
                    $summoner_name = $account_detail['gameName'];
                    $summoner_tagline = $account_detail['tagLine'];
                }
                $name = $summoner_name . '#' . $summoner_tagline;
                $summoner = Summoner::create([
                    'name' => $name,
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
            $team_kills = $teams_kills[$participant['teamId']] == 0 ? 1 : $teams_kills[$participant['teamId']];
            $deaths = $participant['deaths'] == 0 ? 1 : $participant['deaths'];

            $save_data['kill_participation'] = round(($participant['kills'] + $participant['assists']) / $team_kills, 2);
            $save_data['kda'] = round(($participant['kills'] + $participant['assists']) / $deaths, 2);
            $save_items = [];
            $items = [
                $participant['item0'],
                $participant['item1'],
                $participant['item2'],
                $participant['item3'],
                $participant['item4'],
                $participant['item5'],
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
                $item['summoner_match_id'] = $summoner_match->id;
                SummonerMatchItem::create($item);
            }
            $perks['summoner_match_id'] = $summoner_match->id;
            SummonerMatchPerk::create($perks);
        }

        $match->match_creation = Carbon::createFromTimestampMs($api_match['info']['gameCreation']);
        $match->match_end = Carbon::createFromTimestampMs($api_match['info']['gameEndTimestamp']);
        $match->match_duration = gmdate('H:i:s', $api_match['info']['gameDuration']);
        $match->map_id = $map->id;
        $match->queue_id = $queue->id;
        $match->mode_id = $mode->id;
        $match->updated = true;

        $match->save();
        SummonerUpdated::dispatch($this->id, true);

        return true;
    }
}
