<?php

namespace App\Traits;

use App\Enums\PlatformType;
use App\Enums\RegionType;
use App\Events\SummonerUpdated;
use App\Http\Integrations\LolApi\LolLiveGameConnector;
use App\Http\Integrations\LolApi\LolMatchConnector;
use App\Http\Integrations\LolApi\LolMatchDetailsConnector;
use App\Http\Integrations\LolApi\LolMatchIdsConnector;
use App\Http\Integrations\LolApi\LolSummonerByNameConnector;
use App\Http\Integrations\LolApi\LolSummonerByPuuidConnector;
use App\Http\Integrations\LolApi\LolSummonerLeagueConnector;
use App\Http\Integrations\LolApi\Requests\LiveGameRequest;
use App\Http\Integrations\LolApi\Requests\MatchDetailsRequest;
use App\Http\Integrations\LolApi\Requests\MatchIdsRequest;
use App\Http\Integrations\LolApi\Requests\MatchRequest;
use App\Http\Integrations\LolApi\Requests\SummonerByNameRequest;
use App\Http\Integrations\LolApi\Requests\SummonerByPuuidRequest;
use App\Http\Integrations\LolApi\Requests\SummonerLeagueRequest;
use Illuminate\Support\Carbon;
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
        $page = 0;
        $start_time = null;
        if ($this->last_time_update) {
            $start_time = Carbon::createFromTimeString($this->last_time_update)->timestamp;
        }
        $api = new LolMatchIdsConnector(RegionType::EUROPE);
        $match_ids = collect([]);
        $response = $this->handleJobRequest(fn() => $api->send(new MatchIdsRequest($this, $page, $start_time)));
        $data = $response->json();

        while (count($data) == 100) {
            $match_ids = $match_ids->merge($data);
            if ($max_match_count != 0 && $match_ids->count() > $max_match_count) {
                return $match_ids->slice(0, $max_match_count);
            }
            $page++;
            $response = $this->handleJobRequest(fn() => $api->send(new MatchIdsRequest($this, $page, $start_time)));
            $data = $response->json();
        }

        return $match_ids->merge($data);
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
        $response = $api->send(new LiveGameRequest($this));

        return $response->json();
    }

    /**
     * @throws \JsonException
     * @throws \Throwable
     */
    public static function getSummonerByName($summoner_name): array
    {
        $api = new LolSummonerByNameConnector(PlatformType::EUW1);
        $response = $api->send(new SummonerByNameRequest($summoner_name));

        return $response->json();
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws \JsonException
     */
    public function getSummonerByPuuid(): array
    {
        $api = new LolSummonerByPuuidConnector(PlatformType::EUW1);
        $response = $this->handleJobRequest(fn() => $api->send(new SummonerByPuuidRequest($this)));

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
            SummonerUpdated::dispatch($this->id, false);
            sleep($seconds + 1);

            return $this->handleJobRequest($fn_call);
        }
    }
}
