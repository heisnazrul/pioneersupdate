<?php

namespace Tests\Feature;

use App\Models\ConversionFee;
use App\Models\ExchangeRate;
use App\Support\CurrencyConverter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExchangeRateRefreshTest extends TestCase
{
    use RefreshDatabase;

    public function test_refresh_command_fetches_and_stores_configured_gbp_rates(): void
    {
        config([
            'services.exchange_rates.api_key' => 'test-key',
            'services.exchange_rates.base_url' => 'https://example.test/v6',
            'services.exchange_rates.target_currencies' => 'USD,SAR,EUR',
        ]);

        Http::fake([
            'https://example.test/v6/test-key/latest/GBP' => Http::response([
                'conversion_rates' => [
                    'USD' => 1.25,
                    'SAR' => 4.70,
                    'EUR' => 1.15,
                ],
            ], 200),
        ]);

        Artisan::call('exchange-rates:refresh-gbp');

        $this->assertDatabaseHas('exchange_rates', [
            'base_currency' => 'GBP',
            'target_currency' => 'USD',
        ]);
        $this->assertDatabaseHas('exchange_rates', [
            'base_currency' => 'GBP',
            'target_currency' => 'SAR',
        ]);
        $this->assertDatabaseHas('exchange_rates', [
            'base_currency' => 'GBP',
            'target_currency' => 'EUR',
        ]);
    }

    public function test_currency_converter_uses_percentage_conversion_fee_like_legacy_backend(): void
    {
        ExchangeRate::create([
            'base_currency' => 'GBP',
            'target_currency' => 'SAR',
            'rate' => 5,
        ]);

        ConversionFee::create([
            'base_currency' => 'GBP',
            'target_currency' => 'SAR',
            'fee' => 10,
        ]);

        $converter = app(CurrencyConverter::class);
        $converted = $converter->toGbpSar(100, 'GBP');

        $this->assertSame(100.0, $converted['gbp']);
        $this->assertSame(550.0, $converted['sar']);
    }
}
