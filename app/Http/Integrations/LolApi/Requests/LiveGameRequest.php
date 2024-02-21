<?php

namespace App\Http\Integrations\LolApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class LiveGameRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $encrypted_summoner_id,
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {

        return "/lol/spectator/v4/active-games/by-summoner/{$this->encrypted_summoner_id}";
    }
}
