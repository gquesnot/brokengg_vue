<?php

namespace App\Http\Integrations\LolApi;

use App\Enums\PlatformType;
use App\Enums\RegionType;
use Illuminate\Support\Facades\Cache;
use Saloon\Contracts\Response;
use Saloon\Contracts\Sender;
use Saloon\Http\Connector;
use Saloon\HttpSender\HttpSender;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class LolBaseConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasRateLimits;

    public function __construct(
        protected RegionType|PlatformType $connector_ref,
    )
    {
    }

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {

        return "https://{$this->connector_ref->name}.api.riotgames.com";
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'X-Riot-Token' => config('services.riot.api_key'),
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [
            'verify' => false,
        ];
    }

    protected function defaultSender(): Sender
    {
        return resolve(HttpSender::class);
    }

    protected function resolveLimits(): array
    {
        return [
            Limit::allow(20)->everySeconds(120),
        ];
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        return new LaravelCacheStore(Cache::store(config('cache.default')));
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'base-connector';
    }

    public function hasRequestFailed(Response $response): ?bool
    {
        return $response->status() === 404 || $response->status() === 403;
    }
}
