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

    private function normalize(string $code): string
    {
        return strtoupper(trim($code));
    }
}
