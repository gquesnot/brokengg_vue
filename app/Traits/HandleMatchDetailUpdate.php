<?php

namespace App\Traits;

use App\Enums\FrameEventType;
use App\Models\Item;
use App\Models\LolMatch;
use App\Models\SummonerMatch;
use App\Models\SummonerMatchFrame;
use App\Models\SummonerMatchFrameEvent;
use Illuminate\Support\Arr;

trait HandleMatchDetailUpdate
{
    public function loadMatchDetail(LolMatch $match)
    {
        $matchDetail = $this->getMatchDetail($match->match_id);
        if (! $matchDetail) {
            return null;
        }

        $this->deleteOldMatchData($match);
        $untrackedItems = $this->getUntrackedItems();
        [$participants, $summoner_match_ids] = $this->getOrderedParticipants($match, $matchDetail);

        $frames = data_get($matchDetail, 'info.frames');
        foreach ($frames as $frame) {
            $this->handleFrameData($frame, $match, $participants, $summoner_match_ids, $untrackedItems);
        }

        $this->storeAllItemEvents($participants);
    }

    private function handleFrameData($frame, LolMatch $match, &$participants, $summoner_match_ids, $untrackedItems)
    {
        $frames_to_add = $this->prepareFrameToAdd($frame, $match, $participants);
        SummonerMatchFrame::upsert(
            $frames_to_add,
            ['match_id', 'summoner_match_id', 'current_timestamp'],
            ['champion_stats', 'damage_stats', 'current_gold', 'gold_per_second', 'jungle_minions_killed', 'level', 'minions_killed', 'position_x', 'position_y', 'total_gold', 'xp', 'time_enemy_spent_controlled']
        );
        $this->updateParticipantsFrameIds($frame, $summoner_match_ids, $participants);
        $events_to_add = $this->prepareEventsToAdd($frame, $participants, $untrackedItems);

        SummonerMatchFrameEvent::upsert($events_to_add, ['current_timestamp', 'type', 'summoner_match_id', 'summoner_match_frame_id'], ['summoner_match_victim_id', 'summoner_match_frame_victim_id', 'position_x', 'position_y', 'item_id', 'skill_slot', 'level_up_type', 'level']);
    }

    private function prepareEventsToAdd($frame, &$participants, $untrackedItems)
    {
        $events_to_add = [];
        foreach ($frame['events'] as $event) {
            if (! isset($event['participantId'])) {
                continue;
            }
            $type = FrameEventType::from($event['type']);
            $item_id = Arr::get($event, 'itemId', null);
            $before_id = Arr::get($event, 'beforeId', null);
            $after_id = Arr::get($event, 'afterId', null);
            if (
                $item_id && in_array($item_id, $untrackedItems)
                || $before_id && in_array($before_id, $untrackedItems)
                || $after_id && in_array($after_id, $untrackedItems)
            ) {
                continue;
            }
            $base = [
                'current_timestamp' => $event['timestamp'],
                'type' => $type,
                'item_id' => $item_id,
                'skill_slot' => Arr::get($event, 'skillSlot', null),
                'level_up_type' => Arr::get($event, 'levelUpType', null),
                'level' => Arr::get($event, 'level', null),
                'position_x' => Arr::get($event, 'position.x', null),
                'position_y' => Arr::get($event, 'position.y', null),
                'summoner_match_victim_id' => null,
                'summoner_match_frame_victim_id' => null,
            ];
            if ($type == FrameEventType::CHAMPION_KILL) {
                $base['summoner_match_id'] = $participants[$event['killerId']]['summoner_match_id'];
                $base['summoner_match_frame_id'] = $participants[$event['killerId']]['summoner_match_frame_id'];
                $base['summoner_match_victim_id'] = $participants[$event['victimId']]['summoner_match_id'];
                $base['summoner_match_frame_victim_id'] = $participants[$event['victimId']]['summoner_match_frame_id'];
            } else {
                $base['summoner_match_id'] = $participants[$event['participantId']]['summoner_match_id'];
                $base['summoner_match_frame_id'] = $participants[$event['participantId']]['summoner_match_frame_id'];
            }

            if (in_array($type, FrameEventType::itemTypes())) {
                $base['before_id'] = $before_id;
                $base['after_id'] = $after_id;
                $participants[$event['participantId']]['item_events'][] = $base;
            } else {
                $events_to_add[] = $base;
            }
        }

        return $events_to_add;
    }

    private function updateParticipantsFrameIds($frame, $summoner_match_ids, &$participants)
    {
        $summoner_match_frames = SummonerMatchFrame::where('current_timestamp', $frame['timestamp'])
            ->whereIn('summoner_match_id', $summoner_match_ids)
            ->get();

        foreach ($participants as $participant_idx => $participant) {
            $participants[$participant_idx]['summoner_match_frame_id'] = $summoner_match_frames->where('summoner_match_id', $participant['summoner_match_id'])->first()->id;
        }
    }

    private function prepareFrameToAdd($frame, LolMatch $match, &$participants)
    {
        $frames_to_add = [];
        foreach ($frame['participantFrames'] as $idx => $participant_frame) {
            $idx = intval($idx);
            $frames_to_add[] = [
                'match_id' => $match->id,
                'summoner_match_id' => $participants[$idx]['summoner_match_id'],
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

        return $frames_to_add;
    }

    private function storeAllItemEvents(&$participants)
    {
        $allItemEvents = [];
        foreach ($participants as $participantIdx => $participant) {

            $participantItemsEvents = $this->getItemEventsForParticipant($participant);
            // reverse array to get correct order
            $allItemEvents = array_merge($allItemEvents, $participantItemsEvents);
        }

        SummonerMatchFrameEvent::upsert($allItemEvents, ['current_timestamp', 'type', 'summoner_match_id', 'summoner_match_frame_id'], ['summoner_match_victim_id', 'summoner_match_frame_victim_id', 'position_x', 'position_y', 'item_id', 'skill_slot', 'level_up_type', 'level']);
    }

    private function deleteOldMatchData(LolMatch $match)
    {
        SummonerMatchFrame::where('match_id', $match->id)->delete();
        SummonerMatchFrameEvent::whereIn('summoner_match_id', SummonerMatch::whereMatchId($match->id)->pluck('id'))->delete();
    }

    private function getUntrackedItems()
    {
        return Item::where(function ($query) {
            $query->where('tags', '=', '[]')
                ->orWhereJsonContains('tags', 'Trinket')
                ->orWhereJsonContains('tags', 'Consumable')
                ->orWhereJsonContains('tags', 'Vision');
        })->get()->pluck('id')->toArray();
    }

    private function getOrderedParticipants(LolMatch $match, $matchDetail)
    {
        $participants = $match->participants()->with('summoner:id,puuid')->get();
        $participantOrdered = [];

        foreach (data_get($matchDetail, 'metadata.participants') as $idx => $puuid) {
            $summonerMatch = $participants->where('summoner.puuid', $puuid)->first();
            if ($summonerMatch) {
                $participantOrdered[$idx + 1] = [
                    'summoner_match_id' => $summonerMatch->id,
                    'summoner_match_frame_id' => null,
                    'item_events' => [],
                ];
            }
        }

        return [$participantOrdered, $participants->pluck('id')->toArray()];
    }

    private function getItemEventsForParticipant($participant)
    {
        $participantItemsEvents = [];
        foreach ($participant['item_events'] as $itemEvent) {
            if (data_get($itemEvent, 'type') == FrameEventType::ITEM_UNDO) {
                $this->applyUndoItemEvent($participantItemsEvents, $itemEvent);
            } else {
                $participantItemsEvents[] = Arr::except($itemEvent, ['before_id', 'after_id']);
            }
        }

        return $participantItemsEvents;
    }

    private function applyUndoItemEvent(array &$all_items_events, array $item_event)
    {
        $this->checkAndUnset($all_items_events, $item_event, 'before_id', FrameEventType::ITEM_PURCHASED);
        $this->checkAndUnset($all_items_events, $item_event, 'after_id', FrameEventType::ITEM_SOLD);
    }

    private function checkAndUnset(array &$all_items_events, array $item_event, $id_key, $eventType)
    {
        if (!$item_event[$id_key] || $item_event[$id_key] == 0) {
            return;
        }
        for ($i = count($all_items_events) - 1; $i >= 0; $i--) {
            $currentEvent = $all_items_events[$i];
            if ($currentEvent['type'] == $eventType && $currentEvent['item_id'] == $item_event[$id_key]) {
                unset($all_items_events[$i]);
                $all_items_events = array_values($all_items_events);
                break;  // exit loop once a matching item has been found and unset
            }
        }
    }
}
