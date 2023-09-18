<?php

namespace App\Events;

use App\Models\Summoner;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SummonerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public int $summoner_id)
    {
        //
    }


    static function call(int $summoner_id){

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('summoner.'.$this->summoner_id),
        ];
    }


    public function broadCastWhen(): bool
    {
        if (Cache::has('summoner-updated-'.$this->summoner_id)) {
            return false;
        }
        Cache::put('summoner-updated-'.$this->summoner_id, now(), 5);
        return true;
    }
}
