<?php

namespace App\Jobs;

use App\Models\Summoner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSummonerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Summoner $summoner)
    {
    }

    public function handle(): void
    {
        $this->summoner->updateSummonerByPuuid();
        $this->summoner->updateMatches();
    }
}
