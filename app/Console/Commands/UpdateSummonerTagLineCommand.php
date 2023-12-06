<?php

namespace App\Console\Commands;

use App\Models\Summoner;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateSummonerTagLineCommand extends Command
{
    protected $signature = 'update:summoner-tag-line';

    protected $description = 'Command description';

    public function handle(): void
    {
        $next_now = Carbon::now()->addMinute();
        $count = 0;
        foreach (Summoner::where('tag_line', '=', null)->cursor() as $summoner) {
            $account = Summoner::getAccountByPuuid($summoner->puuid);
            $summoner->tag_line = $account['tagLine'];
            $summoner->name = $account['gameName'];
            $summoner->save();
            $count++;
            if ($count >= 800) {
                $count = 0;
                $now = Carbon::now();
                if (($next_now->greaterThan($now))) {
                    sleep($now->diffInSeconds($next_now));
                }
                $next_now = Carbon::now()->addMinute();
            }
        }
    }
}
