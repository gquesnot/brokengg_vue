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
        foreach (Summoner::where('tag_line', '=', null)->cursor() as $summoner) {
            try {
                $account = Summoner::getAccountByPuuid($summoner->puuid);
            } catch (\Exception $e) {
                sleep(60);
                $account = Summoner::getAccountByPuuid($summoner->puuid);
            }
            $summoner->tag_line = $account['tagLine'];
            $summoner->name = $account['gameName'];
            $summoner->save();
        }
    }
}
