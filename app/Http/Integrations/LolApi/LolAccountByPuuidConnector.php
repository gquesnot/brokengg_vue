<?php

namespace App\Http\Integrations\LolApi;

use Saloon\RateLimitPlugin\Limit;

class LolAccountByPuuidConnector extends LolBaseConnector
{
    protected function resolveLimits(): array
    {
        return [
            Limit::allow(1000)->everyMinute(),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'account-by-puuid-connector';
    }
}
