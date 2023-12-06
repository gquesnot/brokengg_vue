<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolSummonerLeagueConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(100)->everyMinute()->sleep(),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'summoner-league-connector';
    }
}
