<?php

namespace App\Http\Integrations\LolApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class SummonerByPuuidRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $puuid,
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/lol/summoner/v4/summoners/by-puuid/{$this->puuid}";
    }
}
