<?php

namespace App\Console\Commands;

use App\Enums\PlatformType;
use App\Enums\RegionType;
use App\Http\Integrations\LolApi\Requests\SummonerByPuuidRequest;
use App\Models\Summoner;
use Illuminate\Console\Command;

class TestApiCommand extends Command
{
    protected $signature = 'test:api';

    protected $description = 'Command description';

    public function handle(): void
    {
        $summoner = Summoner::find(1);
        #$summoner->updateSummonerMatchIds();
        $start = microtime(true);
        $summoner->updateSummonerMatches();
        $end = microtime(true);
        ## print duration in seconds without float
        $this->info("Duration: " . round($end - $start, 2) . " seconds");

//        $connector = new \App\Http\Integrations\LolApi\LolSummonerByPuuidConnector(PlatformType::EUW1);
//        $total = 0;
//        $requests = function () use($total, $summoner) {
//            for ($i = 0; $i < 1500; $i++) {
//                yield new SummonerByPuuidRequest($summoner->puuid);
//                $total+=1;
//                $this->info("$total");
//            }
//        };
//        $connector->pool($requests, concurrency: 10)->send()->wait();
    }
}
