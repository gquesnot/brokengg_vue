<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolLiveGameConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(2000)->everySeconds(10),
            Limit::allow(1200000)->everySeconds(seconds: 600),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'live-game-connector';
    }
}
