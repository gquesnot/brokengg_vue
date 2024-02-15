<?php

namespace App\Http\Integrations\LolApi;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\Paginator;
use Saloon\RateLimitPlugin\Limit;

class LolMatchIdsConnector extends LolBaseConnector implements HasPagination
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(2000)->everySeconds(10)->sleep(),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'match-ids-connector';
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends Paginator {
            protected ?int $perPageLimit = 100;
            protected int $currentPage = 0;

            protected function isLastPage(Response $response): bool
            {
                return count($response->json()) < $this->perPageLimit;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json();
            }

            protected function applyPagination(Request $request): Request
            {
                $request->query()->add('start', $this->currentPage * $this->perPageLimit);

                $request->query()->add('count', $this->perPageLimit);
                return $request;
            }
        };
    }

}
