<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SummonerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $summoner_id,
    ) {
    }

    public function broadcastWith(): array
    {
        return [
        ];
    }

    public function broadcastQueue(): string
    {
        return 'broadcast';
    }

    public function broadcastAs(): string
    {
        return 'summoner.updated';
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
