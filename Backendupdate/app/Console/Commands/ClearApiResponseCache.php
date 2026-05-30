<?php

namespace App\Console\Commands;

use App\Support\AdminCacheFlush;
use Illuminate\Console\Command;

class ClearApiResponseCache extends Command
{
    protected $signature = 'cache:clear-api';

    protected $description = 'Clear cached public API JSON responses (catalog/image payloads)';

    public function handle(): int
    {
        foreach (AdminCacheFlush::flushAll() as $step) {
            $this->info($step);
        }

        return self::SUCCESS;
    }
}
