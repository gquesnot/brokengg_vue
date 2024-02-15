<?php

namespace App\Http\Integrations\LolApi\Requests;

use App\Models\Summoner;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class MatchIdsRequest extends Request implements Paginatable
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected Summoner $summoner,
        protected ?int $start_time,
    )
    {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/lol/match/v5/matches/by-puuid/{$this->summoner->puuid}/ids";
    }

    /**
     * Define the query parameters for the request
     */
    public function defaultQuery(): array
    {
        $query = [];
        if ($this->start_time !== null) {
            $query['startTime'] = $this->start_time;
        }

        return $query;
    }
}
