<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSummonersDataJob;
use Illuminate\Console\Command;

class UpdateSummonersDataCommand extends Command
{
    protected $signature = 'update:summoners-data';

    protected $description = 'Command description';

    public function handle(): void
    {
        UpdateSummonersDataJob::dispatchSync();
    }
}
