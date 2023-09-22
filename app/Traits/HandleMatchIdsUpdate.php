<?php

namespace App\Traits;

use App\Models\LolMatch;

trait HandleMatchIdsUpdate
{
    private function updateSummonerMatchIds(): array
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

    private function getAllMatchIds(): array
    {
        $all_match_ids = [];
        foreach ($this->yieldMatchIds() as $match_id) {
            $all_match_ids[] = $match_id;
        }

        return $all_match_ids;
    }

    private function yieldMatchIds(): iterable
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
}
