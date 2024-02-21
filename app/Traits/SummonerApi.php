<?php

namespace App\Traits;

use App\Enums\PlatformType;
use App\Enums\RegionType;
use App\Http\Integrations\LolApi\LolLiveGameConnector;
use App\Http\Integrations\LolApi\LolMatchConnector;
use App\Http\Integrations\LolApi\LolMatchDetailsConnector;
use App\Http\Integrations\LolApi\LolMatchIdsConnector;
use App\Http\Integrations\LolApi\LolSummonerLeagueConnector;
use App\Http\Integrations\LolApi\Requests\LiveGameRequest;
use App\Http\Integrations\LolApi\Requests\MatchDetailsRequest;
use App\Http\Integrations\LolApi\Requests\MatchIdsRequest;
use App\Http\Integrations\LolApi\Requests\MatchRequest;
use App\Http\Integrations\LolApi\Requests\SummonerLeagueRequest;
use Illuminate\Support\Collection;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Response;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;

trait SummonerApi
{
    /**
     * @throws NotFoundException
     * @throws ForbiddenException|\JsonException
     */
    private function getSummonerMatchIds(): Collection
    {
        $max_match_count = config('services.riot.max_match_count');
        $connector = new LolMatchIdsConnector(RegionType::EUROPE);
        $start_time = null;
        $request = new MatchIdsRequest($this, $start_time);
        $paginator = $connector->paginate($request);

        return $paginator->collect()->flatten()->collect();
    }

    /**
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    private function getMatchDetail(string $match_id): array
    {
        $api = new LolMatchDetailsConnector(RegionType::EUROPE);
        $response = $api->send(new MatchDetailsRequest($match_id));

        return $response->json();
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException|\JsonException
     */
    private function getMatch(string $match_id): array
    {
        $api = new LolMatchConnector(RegionType::EUROPE);
        $response = $this->handleJobRequest(fn() => $api->send(new MatchRequest($match_id)));

        return $response->json();
    }

    /**
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getLiveGame(): array
    {
        $api = new LolLiveGameConnector(PlatformType::EUW1);
        $request = new LiveGameRequest($this->summoner_id);
        $response = $api->send($request);
        return $response->json();
    }

    public function getSummonerLeague(): array
    {
        $api = new LolSummonerLeagueConnector(PlatformType::EUW1);
        $response = $api->send(new SummonerLeagueRequest($this));

        return $response->json();
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    private function handleJobRequest(callable $fn_call): Response
    {
        try {
            return $fn_call();
        } catch (RateLimitReachedException $e) {
            $seconds = $e->getLimit()->getRemainingSeconds();
            sleep($seconds + 1);

            return $this->handleJobRequest($fn_call);
        }
    }
}
