<?php

namespace App\Console\Commands;

use App\Enums\PlatformType;
use App\Enums\RegionType;
use App\Http\Integrations\LolApi\Requests\SummonerByPuuidRequest;
use App\Jobs\UpdateSummonerJob;
use App\Jobs\UpdateSummonersDataJob;
use App\Models\Summoner;
use Illuminate\Console\Command;

class TestApiCommand extends Command
{
    protected $signature = 'test:api';

    protected $description = 'Command description';

    public function handle(): void
    {
        $summoner = Summoner::find(1);
        UpdateSummonerJob::dispatchSync($summoner);
    }
}
