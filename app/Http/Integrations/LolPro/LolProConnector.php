<?php

namespace App\Http\Integrations\LolPro;

use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\AcceptsJson;

class LolProConnector extends Connector implements HasPagination
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.lolpros.gg/es';
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'LPGG-server' => 'EUW',
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [
            'verify' => false,
        ];
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends Paginator {
            protected ?int $perPageLimit = 1000;

            protected function isLastPage(Response $response): bool
            {
                return count($response->json()) != 1000;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json();
            }

            protected function applyPagination(Request $request): Request
            {
                $request->query()->add('page', $this->page);
                $request->query()->add('page_size', $this->perPageLimit);

                return $request;
            }
        };
    }
}
