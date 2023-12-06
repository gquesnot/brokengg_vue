<?php

namespace App\Http\Integrations\LolApi\Requests;

use App\Models\Summoner;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class SummonerLeagueRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected Summoner $summoner,
    )
    {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/lol/league/v4/entries/by-summoner/{$this->summoner->summoner_id}";
    }
}
