<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExchangeRateRefreshService
{
    private const DEFAULT_BASE_CURRENCY = 'GBP';

    private const DEFAULT_TARGET_CURRENCIES = [
        'INR', 'CNY', 'JPY', 'PKR', 'BDT', 'ZAR', 'SGD', 'SAR', 'KWD', 'OMR',
        'BHD', 'QAR', 'EGP', 'LYD', 'IRR', 'MYR', 'THB', 'IDR', 'PHP', 'VND',
        'KOR', 'TWD', 'AUD', 'NZD', 'AFN', 'LKR', 'USD', 'EUR', 'CAD', 'CHF', 'AED',
    ];

    public function refreshGbpRates(?array $targetCurrencies = null): array
    {
        $baseCurrency = self::DEFAULT_BASE_CURRENCY;
        $targets = $this->normalizeCurrencies($targetCurrencies ?: $this->configuredTargets());

        if ($targets === []) {
            throw new RuntimeException('No target currencies are configured for exchange rate refresh.');
        }

        $apiKey = trim((string) config('services.exchange_rates.api_key'));
        if ($apiKey === '') {
            throw new RuntimeException('Missing EXCHANGE_RATE_API_KEY configuration.');
        }

        $baseUrl = rtrim((string) config('services.exchange_rates.base_url', 'https://v6.exchangerate-api.com/v6'), '/');
        $timeout = max(1, (int) config('services.exchange_rates.timeout', 15));

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->get("{$baseUrl}/{$apiKey}/latest/{$baseCurrency}");

        if (!$response->successful()) {
            throw new RuntimeException("Provider returned HTTP {$response->status()}.");
        }

        $rates = $response->json('conversion_rates');
        if (!is_array($rates)) {
            throw new RuntimeException('Provider response did not include conversion rates.');
        }

        $updated = 0;
        $missing = [];

        foreach ($targets as $targetCurrency) {
            if (!array_key_exists($targetCurrency, $rates)) {
                $missing[] = $targetCurrency;
                continue;
            }

            ExchangeRate::updateOrCreate(
                [
                    'base_currency' => $baseCurrency,
                    'target_currency' => $targetCurrency,
                ],
                [
                    'rate' => (float) $rates[$targetCurrency],
                ]
            );

            $updated++;
        }

        return [
            'base_currency' => $baseCurrency,
            'updated_rates' => $updated,
            'requested_targets' => count($targets),
            'missing_targets' => $missing,
        ];
    }

    private function configuredTargets(): array
    {
        $configured = config('services.exchange_rates.target_currencies');

        if (is_array($configured) && $configured !== []) {
            return $configured;
        }

        if (is_string($configured) && trim($configured) !== '') {
            return explode(',', $configured);
        }

        return self::DEFAULT_TARGET_CURRENCIES;
    }

    private function normalizeCurrencies(array $currencies): array
    {
        return collect($currencies)
            ->map(fn (mixed $currency) => strtoupper(trim((string) $currency)))
            ->filter(fn (string $currency) => strlen($currency) === 3)
            ->unique()
            ->values()
            ->all();
    }
}
