<?php

namespace App\Jobs;

use App\Events\SummonerUpdated;
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

        SummonerUpdated::dispatch($this->summoner->id, true);
        $account = Summoner::getAccountByPuuid($this->summoner->puuid);
        $this->summoner->name = $account['gameName'] . '#' . $account['tagLine'];
        $this->summoner->updateSummonerFromArray(Summoner::getSummonerByPuuid($this->summoner->puuid));
        $this->summoner->updateSummonerLeague();

        $this->summoner->updateSummonerMatchIds();
        $this->summoner->updateSummonerMatches();

        $last_match = $this->summoner->matches()->orderBy('match_id', 'desc')->first();
        if ($last_match) {
            $this->summoner->last_time_update = $last_match->match_creation;
            $this->summoner->save();
        }
        SummonerUpdated::dispatch($this->summoner->id, false);
    }
}
