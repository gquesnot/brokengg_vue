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

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $summoner_id,
        public bool $is_after_update = false
    ) {
        //
    }

    public function broadCastWhen(): bool
    {
        if ($this->is_after_update || ! Cache::has('summoner-updated-'.$this->summoner_id)) {
            if (now()->addSeconds(3) < Cache::get('summoner-updated-'.$this->summoner_id)) {
                return false;
            }
            Cache::put('summoner-updated-'.$this->summoner_id, now());

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
            new Channel('summoner-'.$this->summoner_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'summoner-updated';
    }
}
