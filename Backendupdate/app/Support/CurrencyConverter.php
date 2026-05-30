<?php

namespace App\Support;

use App\Models\ConversionFee;
use App\Models\ExchangeRate;

class CurrencyConverter
{
    private array $rateCache = [];

    private array $feeCache = [];

    public function toGbpSar(float|int|string|null $amount, ?string $currency): array
    {
        $base = $this->normalize($currency ?: 'GBP');

        return [
            'gbp' => $this->convert($amount, $base, 'GBP', false),
            'sar' => $this->convert($amount, $base, 'SAR', true),
        ];
    }

    /**
     * @return list<string>
     */
    public function availableCurrencyCodes(): array
    {
        $codes = ExchangeRate::query()
            ->get(['base_currency', 'target_currency'])
            ->flatMap(fn (ExchangeRate $rate) => [$rate->base_currency, $rate->target_currency])
            ->map(fn ($code) => $this->normalize((string) $code))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return $codes;
    }

    /**
     * @return array<string, float>
     */
    public function buildPriceMap(float|int|string|null $amount, string $baseCurrency): array
    {
        if ($amount === null || $amount === '') {
            return [];
        }

        $base = $this->normalize($baseCurrency ?: 'GBP');
        $map = [];

        foreach ($this->availableCurrencyCodes() as $target) {
            $fee = $this->feePercent($base, $target);
            $converted = $this->convert($amount, $base, $target, $fee > 0);

            if ($converted !== null) {
                $map[$target] = $converted;
            }
        }

        if (! array_key_exists($base, $map)) {
            $map[$base] = round((float) $amount, 2);
        }

        ksort($map);

        return $map;
    }

    public function convert(float|int|string|null $amount, string $base, string $target, bool $applyFee): ?float
    {
        if ($amount === null || $amount === '') {
            return null;
        }

        $value = (float) $amount;
        $base = $this->normalize($base);
        $target = $this->normalize($target);

        $rate = $this->rate($base, $target);
        if ($rate === null) {
            return null;
        }

        $converted = $value * $rate;

        if ($applyFee) {
            $fee = $this->feePercent($base, $target);
            if ($fee > 0) {
                $converted *= (1 + ($fee / 100));
            }
        }

        return round($converted, 2);
    }

    private function rate(string $base, string $target): ?float
    {
        if ($base === $target) {
            return 1.0;
        }

        $cacheKey = $base . ':' . $target;
        if (array_key_exists($cacheKey, $this->rateCache)) {
            return $this->rateCache[$cacheKey];
        }

        $direct = ExchangeRate::query()
            ->where('base_currency', $base)
            ->where('target_currency', $target)
            ->value('rate');

        if ($direct !== null) {
            return $this->rateCache[$cacheKey] = (float) $direct;
        }

        if ($target === 'GBP') {
            $gbpToBase = ExchangeRate::query()
                ->where('base_currency', 'GBP')
                ->where('target_currency', $base)
                ->value('rate');

            if ($gbpToBase) {
                return $this->rateCache[$cacheKey] = 1 / (float) $gbpToBase;
            }
        }

        if ($base !== 'GBP' && $target !== 'GBP') {
            $gbpToBase = ExchangeRate::query()
                ->where('base_currency', 'GBP')
                ->where('target_currency', $base)
                ->value('rate');
            $gbpToTarget = ExchangeRate::query()
                ->where('base_currency', 'GBP')
                ->where('target_currency', $target)
                ->value('rate');

            if ($gbpToBase && $gbpToTarget) {
                return $this->rateCache[$cacheKey] = (1 / (float) $gbpToBase) * (float) $gbpToTarget;
            }
        }

        return $this->rateCache[$cacheKey] = null;
    }

    private function feePercent(string $base, string $target): float
    {
        $cacheKey = $base . ':' . $target;
        if (array_key_exists($cacheKey, $this->feeCache)) {
            return $this->feeCache[$cacheKey];
        }

        $fee = ConversionFee::query()
            ->where('base_currency', $base)
            ->where('target_currency', $target)
            ->value('fee');

        if ($fee === null && $target === 'SAR' && $base !== 'GBP') {
            $fee = ConversionFee::query()
                ->where('base_currency', 'GBP')
                ->where('target_currency', 'SAR')
                ->value('fee');
        }

        return $this->feeCache[$cacheKey] = (float) ($fee ?? 0);
    }

    public function getExchangeRate(string $baseCurrency, string $targetCurrency): ?float
    {
        return $this->rate($this->normalize($baseCurrency), $this->normalize($targetCurrency));
    }

    public function getConversionFeePercent(string $baseCurrency, string $targetCurrency): float
    {
        return $this->feePercent($this->normalize($baseCurrency), $this->normalize($targetCurrency));
    }

    /**
     * @return array<string, mixed>
     */
    public function bookingCurrencySnapshot(string $baseCurrency, string $displayCurrency, float $baseTotal): array
    {
        $base = $this->normalize($baseCurrency ?: 'GBP');
        $display = $this->normalize($displayCurrency ?: $base);
        $rate = $this->getExchangeRate($base, $display);
        $feePercent = $this->getConversionFeePercent($base, $display);
        $converted = $this->convert($baseTotal, $base, $display, $feePercent > 0);
        $feeAmount = $converted !== null && $feePercent > 0
            ? round($baseTotal * ($rate ?? 0) * ($feePercent / 100), 2)
            : 0.0;

        $ratesAtBooking = [];
        foreach ($this->availableCurrencyCodes() as $code) {
            $codeRate = $this->getExchangeRate($base, $code);
            if ($codeRate !== null) {
                $ratesAtBooking[$code] = round($codeRate, 6);
            }
        }

        if (! array_key_exists($base, $ratesAtBooking)) {
            $ratesAtBooking[$base] = 1.0;
        }

        return [
            'base_currency' => $base,
            'display_currency' => $display,
            'exchange_rate' => $rate,
            'conversion_fee_percent' => $feePercent,
            'conversion_fee_amount' => $feeAmount,
            'base_total' => round($baseTotal, 2),
            'display_total' => $converted,
            'rates_at_booking' => $ratesAtBooking,
            'captured_at' => now()->toIso8601String(),
        ];
    }

    private function normalize(string $code): string
    {
        return strtoupper(trim($code));
    }
}
