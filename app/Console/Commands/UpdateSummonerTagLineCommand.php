<?php

namespace App\Console\Commands;

use App\Models\Summoner;
use Illuminate\Console\Command;

class UpdateSummonerTagLineCommand extends Command
{
    protected $signature = 'update:summoner-tag-line';

    protected $description = 'Command description';

    public function handle(): void
    {
        $count = 0;
        foreach (Summoner::where('tag_line', '=', null)->cursor() as $summoner) {
            $account = Summoner::getAccountByPuuid($summoner->puuid);
            $summoner->tag_line = $account['tagLine'];
            $summoner->name = $account['gameName'];
            $summoner->save();
            $count++;
            if ($count >= 800) {
                $count = 0;
                sleep(60);
            }
        }
    }
}
