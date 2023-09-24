<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolMatchConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(80)->everySeconds(120),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'match-connector';
    }
}
