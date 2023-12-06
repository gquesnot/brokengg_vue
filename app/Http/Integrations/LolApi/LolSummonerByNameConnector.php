<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolSummonerByNameConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(1600)->everyMinute(),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'summoner-by-name-connector';
    }
}
