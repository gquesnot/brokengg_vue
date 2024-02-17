<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSummonerJob;
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
