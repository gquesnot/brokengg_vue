<?php

namespace App\Traits;

use App\Models\LolMatch;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;

trait HandleMatchIdsUpdate
{
    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function updateSummonerMatchIds()
    {
        $match_ids = $this->getSummonerMatchIds();
        $present_match_ids = LolMatch::whereIn('match_id', $match_ids)->pluck('match_id');
        $match_ids = $match_ids->diff($present_match_ids);
        $db_match_ids = $match_ids
            ->map(function ($match_id) {
                return [
                    'match_id' => $match_id,
                ];
            })
            ->toArray();
        LolMatch::upsert($db_match_ids, ['match_id'], ['match_id']);
    }
}
