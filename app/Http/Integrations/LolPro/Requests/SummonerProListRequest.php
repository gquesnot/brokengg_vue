<?php

namespace App\Http\Integrations\LolPro\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class SummonerProListRequest extends Request implements Paginatable
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/ladder';
    }

    public function defaultQuery(): array
    {
        return [
            'sort' => 'rank',
            'order' => 'desc',
        ];
    }
}
