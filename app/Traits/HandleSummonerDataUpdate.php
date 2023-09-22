<?php

namespace App\Traits;

use App\Models\Summoner;

trait HandleSummonerDataUpdate
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
        $summoner_array = $this->getSummonerByPuuid();
        if (! $summoner_array) {
            return;
        }
        $this->updateSummonerWithArray($summoner_array);
    }

    private function updateSummonerWithArray(array $summoner_array)
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
