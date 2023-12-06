<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolMatchIdsConnector extends LolBaseConnector
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
}
