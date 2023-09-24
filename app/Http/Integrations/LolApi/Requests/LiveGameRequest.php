<?php

namespace App\Http\Integrations\LolApi\Requests;

use App\Models\Summoner;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class LiveGameRequest extends Request
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
        return "/lol/spectator/v4/active-games/by-summoner/{$this->summoner->summoner_id}";
    }
}
