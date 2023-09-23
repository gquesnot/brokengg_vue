<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SummonerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $summoner_id,
        public bool $should_start_refresh,
    ) {
    }

    public function broadcastWith(): array
    {
        return [
            'should_start_refresh' => $this->should_start_refresh,
        ];
    }

    public function broadcastWhen(): bool
    {
        $cache_key = 'summoner.' . $this->summoner_id;
        $previous_value = Cache::get($cache_key, null);

        if ($previous_value !== $this->should_start_refresh) {
            Cache::put($cache_key, $this->should_start_refresh);

            return true;
        }

        return false;
    }

    public function broadcastQueue(): string
    {
        return 'broadcast';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('summoner.'.$this->summoner_id),
        ];
    }
}
