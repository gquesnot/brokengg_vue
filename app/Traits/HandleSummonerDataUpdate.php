<?php

namespace App\Traits;

use App\Models\Summoner;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;

trait HandleSummonerDataUpdate
{
    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws RateLimitReachedException
     */
    public static function updateOrCreateSummonerByName(string $summoner_name): ?Summoner
    {

        $summoner_array = Summoner::getSummonerByName($summoner_name);
        if (Summoner::wherePuuid($summoner_array['puuid'])->exists()) {
            $summoner = Summoner::wherePuuid($summoner_array['puuid'])->first();
        } else {
            $summoner = new Summoner();
        }
        $summoner->updateSummonerFromArray($summoner_array);

        return $summoner;
    }

    public function updateSummonerFromArray(array $summoner_array): void
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
}
