<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolLiveGameConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(2000)->everySeconds(10),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'live-game-connector';
    }
}
