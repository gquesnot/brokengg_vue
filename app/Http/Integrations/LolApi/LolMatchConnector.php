<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolMatchConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(2000)->everySeconds(10)->sleep(),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'match-connector';
    }
}
