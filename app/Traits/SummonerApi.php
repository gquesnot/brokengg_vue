<?php

namespace App\Traits;

use App\Models\Summoner;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

trait SummonerApi
{
    private function getSummonerMatchIds($start = 0, $count = 100)
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

    private function getMatchDetail(string $match_id)
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

    private function getMatch(string $match_id)
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

    private function getSummonerByPuuid()
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

    private static function responseLimitExceeded($data): bool
    {
        return Arr::get($data, 'status.status_code') == 429;
    }

    private static function responseNotFound($data)
    {
        return Arr::get($data, 'status.status_code') == 404;
    }

    private static function responseIsForbidden($data)
    {
        return Arr::get($data, 'status.status_code') == 403;
    }
}
