<?php

namespace App\Jobs;

use App\Models\Summoner;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSummonersDataJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('summoners-data');
    }

    public function handle(): void
    {
        $query = Summoner::where('updated_at', '<', Carbon::now()->subHours(24)->toDateTimeString())->orWhere('account_id', null);
        $next_now = Carbon::now()->addMinute();
        $current_summoner_update_count = 0;
        foreach ($query->cursor() as $summoner) {
            $account = Summoner::getAccountByPuuid($summoner->puuid);
            $summoner->name = $account['gameName'] . '#' . $account['tagLine'];
            $summoner->updateSummonerFromArray(Summoner::getSummonerByPuuid($summoner->puuid));
            $summoner->updateSummonerLeague();
            $current_summoner_update_count++;
            if ($current_summoner_update_count >= 40) {
                $current_summoner_update_count = 0;
                $now = Carbon::now();
                if (($next_now->greaterThan($now))) {
                    sleep($now->diffInSeconds($next_now));
                }
                $next_now = Carbon::now()->addMinute();
            }
        }
    }
}
