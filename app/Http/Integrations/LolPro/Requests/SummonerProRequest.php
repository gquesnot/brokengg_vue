<?php

namespace App\Http\Integrations\LolPro\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class SummonerProRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        public string $summoner_slug
    )
    {

    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/profiles/' . $this->summoner_slug;
    }
}
