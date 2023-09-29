<?php

namespace App\Console\Commands;

use App\Helpers\UpdateProPlayerHelper;
use Illuminate\Console\Command;

class UpdateProPlayerCommand extends Command
{
    protected $signature = 'update:pro-player';

    protected $description = 'Update pro player';

    public function handle(): void
    {
        UpdateProPlayerHelper::updateProPlayer($this->output);
    }
}
