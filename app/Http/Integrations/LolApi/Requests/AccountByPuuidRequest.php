<?php

namespace App\Http\Integrations\LolApi\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class AccountByPuuidRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $puuid
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/riot/account/v1/accounts/by-puuid/{$this->puuid}";
    }
}
