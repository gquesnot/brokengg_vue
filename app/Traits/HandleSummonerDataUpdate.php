<?php

namespace App\Traits;

use App\Enums\LeagueRankType;
use App\Enums\LeagueTierType;
use App\Enums\LeagueType;
use App\Enums\PlatformType;
use App\Enums\RegionType;
use App\Helpers\LeaguePositionHelper;
use App\Http\Integrations\LolApi\LolAccountByNameAndTagLineConnector;
use App\Http\Integrations\LolApi\LolAccountByPuuidConnector;
use App\Http\Integrations\LolApi\LolSummonerByPuuidConnector;
use App\Http\Integrations\LolApi\Requests\AccountByNameAndTagLineRequest;
use App\Http\Integrations\LolApi\Requests\AccountByPuuidRequest;
use App\Http\Integrations\LolApi\Requests\SummonerByPuuidRequest;
use App\Models\Summoner;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;

trait HandleSummonerDataUpdate
{
    /**
     * @throws \JsonException
     * @throws \Throwable
     */
    public static function getAccountByNameAndTagLine(string $summoner_name, string $tag_line): array
    {
        $api = new LolAccountByNameAndTagLineConnector(RegionType::EUROPE);
        $response = $api->send(new AccountByNameAndTagLineRequest($summoner_name, $tag_line));

        return $response->json();
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws RateLimitReachedException
     */
    public static function updateOrCreateSummonerByNameAndTagLine(string $summoner_name): ?Summoner
    {
        [$summoner_name, $tag_line] = explode('#', $summoner_name);
        $account_info = Summoner::getAccountByNameAndTagLine($summoner_name, $tag_line);
        $puuid = $account_info['puuid'];
        $summoner_array = Summoner::getSummonerByPuuid($puuid);
        if (Summoner::wherePuuid($summoner_array['puuid'])->exists()) {
            $summoner = Summoner::wherePuuid($summoner_array['puuid'])->first();
        } else {
            $summoner = new Summoner();
        }
        $summoner->name = $account_info['gameName'] . '#' . $account_info['tagLine'];
        $summoner->updateSummonerFromArray($summoner_array);

        return $summoner;
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws \JsonException
     */
    public static function getSummonerByPuuid(string $puuid): array
    {
        $api = new LolSummonerByPuuidConnector(PlatformType::EUW1);
        $response = $api->send(new SummonerByPuuidRequest($puuid));

        return $response->json();
    }

    public static function getAccountByPuuid(string $puuid): array
    {
        $api = new LolAccountByPuuidConnector(RegionType::EUROPE);
        $response = $api->send(new AccountByPuuidRequest($puuid));

        return $response->json();
    }

    public function updateSummonerFromArray(array $summoner_array): void
    {
        $this->puuid = $summoner_array['puuid'];
        $this->summoner_level = $summoner_array['summonerLevel'];
        $this->profile_icon_id = $summoner_array['profileIconId'];
        $this->revision_date = $summoner_array['revisionDate'];
        $this->account_id = $summoner_array['accountId'];
        $this->summoner_id = $summoner_array['id'];
        $this->save();
    }

    public function updateSummonerLeague(): void
    {
        $summoner_league_array = $this->getSummonerLeague();
        $this->leagues()->delete();
        foreach ($summoner_league_array as $league_array) {
            $tier = LeagueTierType::from($league_array['tier']);
            $rank = LeagueRankType::from($league_array['rank']);

            $overall_position = LeaguePositionHelper::getOverallPosition($tier, $rank);
            $this->leagues()->create([
                'type' => LeagueType::from($league_array['queueType']),
                'tier' => $tier,
                'rank' => $rank,
                'overall_position' => $overall_position,
            ]);
        }

    }
}
