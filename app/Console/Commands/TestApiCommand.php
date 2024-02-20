<?php

namespace App\Console\Commands;

use App\Jobs\UpdateDragonDataJob;
use Illuminate\Console\Command;

class TestApiCommand extends Command
{
    protected $signature = 'test:api';

    protected $description = 'Command description';

    public function handle(): void
    {
        UpdateDragonDataJob::dispatchSync();
    }
}
