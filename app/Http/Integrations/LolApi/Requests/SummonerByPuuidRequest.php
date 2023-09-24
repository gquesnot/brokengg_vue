<?php

namespace App\Http\Integrations\LolApi\Requests;

use App\Models\Summoner;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class SummonerByPuuidRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected Summoner $summoner,
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/lol/summoner/v4/summoners/by-puuid/{$this->summoner->puuid}";
    }
}
