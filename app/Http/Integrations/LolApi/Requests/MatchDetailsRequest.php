<?php

namespace App\Http\Integrations\LolApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class MatchDetailsRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $match_id,
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/lol/match/v5/matches/{$this->match_id}/timeline";
    }
}
