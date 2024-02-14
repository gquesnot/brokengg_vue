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
        foreach (Summoner::where('tag_line', '=', null)->cursor() as $summoner) {
            $account = Summoner::getAccountByPuuid($summoner->puuid);
            $summoner->name = $account['gameName'] . '#' . $account['tagLine'];
            $summoner->save();
        }
    }
}
