<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolSummonerByPuuidConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(1600)->everySeconds(10),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'summoner-by-puuid-connector';
    }
}
