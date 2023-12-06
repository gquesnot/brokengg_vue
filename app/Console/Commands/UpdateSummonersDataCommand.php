<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSummonersDataJob;
use Illuminate\Console\Command;

class UpdateSummonersDataCommand extends Command
{
    protected $signature = 'update:summoners-data';

    protected $description = 'Update summoners data & leagues';

    public function handle(): void
    {
        UpdateSummonersDataJob::dispatch();
    }
}
