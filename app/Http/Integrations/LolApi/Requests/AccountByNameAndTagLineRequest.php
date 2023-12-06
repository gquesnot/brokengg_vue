<?php

namespace App\Http\Integrations\LolApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class AccountByNameAndTagLineRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $summoner_name,
        protected string $tag_line,
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/riot/account/v1/accounts/by-riot-id/{$this->summoner_name}/{$this->tag_line}";
    }
}
