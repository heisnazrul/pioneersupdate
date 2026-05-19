<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateRefreshService;
use Illuminate\Console\Command;
use Throwable;

class RefreshExchangeRatesCommand extends Command
{
    protected $signature = 'exchange-rates:refresh-gbp {--targets= : Comma-separated ISO codes to refresh instead of the configured list}';

    protected $description = 'Fetch and store GBP exchange rates from the configured provider.';

    public function handle(ExchangeRateRefreshService $service): int
    {
        $targets = $this->option('targets');
        $targetCurrencies = is_string($targets) && trim($targets) !== ''
            ? explode(',', $targets)
            : null;

        try {
            $summary = $service->refreshGbpRates($targetCurrencies);
        } catch (Throwable $e) {
            report($e);
            $this->error('Exchange rate refresh failed: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->info("Updated {$summary['updated_rates']} GBP exchange rates.");

        if ($summary['missing_targets'] !== []) {
            $this->warn('Missing targets: ' . implode(', ', $summary['missing_targets']));
        }

        return self::SUCCESS;
    }
}
