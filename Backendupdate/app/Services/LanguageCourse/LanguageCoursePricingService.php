<?php

namespace App\Services\LanguageCourse;

class LanguageCoursePricingService
{
    private const FREE_FOR_ALIASES = [
        'pickup' => 'pickup',
        'airport_pickup' => 'pickup',
        'airport pickup' => 'pickup',
        'insurance' => 'insurance',
        'registration' => 'registration',
        'registration_fee' => 'registration',
        'material_books' => 'material_books',
        'material' => 'material_books',
        'books' => 'material_books',
        'accommodation' => 'accommodation',
        'acc' => 'accommodation',
    ];

    /**
     * @param  array<string, mixed>|null  $selectedCourse
     * @param  array<string, mixed>|null  $selectedAccommodation
     * @param  array<string, mixed>|null  $selectedPickup
     * @param  list<array<string, mixed>>  $selectedInsurances
     * @param  list<array<string, mixed>>  $selectedSupplements
     * @param  array<string, mixed>|null  $registrationFeeObj
     * @param  list<array<string, mixed>>  $pioneersDiscounts
     * @param  list<array<string, mixed>>  $discounts
     * @param  array<string, string>  $supplementLabels
     * @return array<string, mixed>
     */
    public function compute(array $params): array
    {
        $selectedCourse = $params['selectedCourse'] ?? null;
        $selectedCourseId = $params['selectedCourseId'] ?? null;
        $selectedAccommodation = $params['selectedAccommodation'] ?? null;
        $selectedPickup = $params['selectedPickup'] ?? null;
        $selectedInsurances = $params['selectedInsurances'] ?? [];
        $selectedSupplements = $params['selectedSupplements'] ?? [];
        $weeks = max(1, (int) ($params['weeks'] ?? 1));
        $startDate = $params['startDate'] ?? null;
        $accAge = $params['accAge'] ?? null;
        $currency = strtoupper((string) ($params['currency'] ?? 'GBP'));
        $registrationFeeObj = $params['registrationFeeObj'] ?? null;
        $pioneersDiscounts = $params['pioneersDiscounts'] ?? [];
        $discounts = $params['discounts'] ?? [];
        $supplementLabels = $params['supplementLabels'] ?? [];

        $courseDiscountPercent = $this->resolveCoursePromotionPercent(
            $selectedCourseId,
            $discounts,
            $selectedCourse,
        );

        $weeklyCourseFee = $selectedCourse
            ? $this->resolveWeeklyCourseFee($selectedCourse, $weeks, $currency)
            : 0.0;
        $courseTotal = $weeklyCourseFee * $weeks;

        $weeklyAccFee = $selectedAccommodation
            ? $this->getItemPrice($selectedAccommodation, $currency, 'fee_per_week', 'fee_per_week_prices')
            : 0.0;
        $accTotal = $selectedAccommodation ? $weeklyAccFee * $weeks : 0.0;

        $oneTimeFees = [];

        if ($selectedCourse) {
            $materialFee = $this->getItemPrice($selectedCourse, $currency, 'material_books_fee', 'material_books_prices');
            if ($materialFee > 0) {
                $oneTimeFees[] = [
                    'key' => 'material_books',
                    'label' => $supplementLabels['material_books'] ?? 'Material / Books Fee',
                    'total' => $materialFee,
                ];
            }

            $regFee = $this->getItemPrice($selectedCourse, $currency, 'registration_admin_fee', 'registration_admin_prices');
            if ($regFee > 0) {
                $oneTimeFees[] = [
                    'key' => 'registration',
                    'label' => $supplementLabels['registration'] ?? 'Registration Fee',
                    'total' => $regFee,
                ];
            }

            $mandatoryFee = $this->getItemPrice($selectedCourse, $currency, 'mandatory_additional_fee', 'mandatory_additional_prices');
            if ($mandatoryFee > 0) {
                $oneTimeFees[] = [
                    'key' => 'mandatory',
                    'label' => $selectedCourse['mandatory_additional_fee_name']
                        ?? ($supplementLabels['mandatory'] ?? 'Mandatory Fee'),
                    'total' => $mandatoryFee,
                ];
            }
        } elseif ($registrationFeeObj) {
            $regFee = $this->getItemPrice($registrationFeeObj, $currency, 'amount', 'prices');
            if ($regFee > 0) {
                $oneTimeFees[] = [
                    'key' => 'registration',
                    'label' => $supplementLabels['registration'] ?? 'Registration Fee',
                    'total' => $regFee,
                ];
            }
        }

        $accSupplements = $selectedAccommodation
            ? $this->getAccommodationSupplements($selectedAccommodation, $weeks, $startDate, $accAge, $currency, $supplementLabels)
            : [];

        $pickupTotal = $selectedPickup
            ? $this->getItemPrice($selectedPickup, $currency, 'price', 'prices')
            : 0.0;

        $insuranceLines = [];
        foreach ($selectedInsurances as $insurance) {
            $insuranceLines = array_merge(
                $insuranceLines,
                $this->getInsurancePricing($insurance, $weeks, $currency, $supplementLabels)['lines'],
            );
        }
        $insuranceTotal = array_sum(array_column($insuranceLines, 'total'));

        $supplementLines = [];
        foreach ($selectedSupplements as $supplement) {
            $total = $this->getItemPrice($supplement, $currency, 'price', 'prices')
                ?: $this->getItemPrice($supplement, $currency, 'amount', 'prices');
            if ($total > 0) {
                $supplementLines[] = [
                    'key' => 'supplement_' . ($supplement['id'] ?? ''),
                    'id' => $supplement['id'] ?? null,
                    'label' => $supplement['name'] ?? '',
                    'ar_label' => $supplement['ar_name'] ?? null,
                    'total' => $total,
                    'perWeek' => false,
                ];
            }
        }
        $supplementsExtraTotal = array_sum(array_column($supplementLines, 'total'));

        $pioneers = $this->applyPioneersBenefits([
            'selectedWeeks' => $weeks,
            'pioneersDiscounts' => $pioneersDiscounts,
            'currency' => $currency,
            'pickupTotal' => $pickupTotal,
            'insuranceTotal' => $insuranceTotal,
            'insuranceLines' => $insuranceLines,
            'accTotal' => $accTotal,
            'oneTimeFees' => $oneTimeFees,
        ]);

        $adjustedOneTimeTotal = array_sum(array_column($pioneers['adjustedOneTimeFees'], 'total'));
        $accSupplementsTotal = array_sum(array_column($accSupplements, 'total'));

        $courseDiscountAmount = $courseDiscountPercent > 0
            ? $courseTotal * ($courseDiscountPercent / 100)
            : 0.0;

        $subtotal = $courseTotal
            + $pioneers['adjustedAccTotal']
            + $adjustedOneTimeTotal
            + $accSupplementsTotal
            + $pioneers['adjustedPickupTotal']
            + $pioneers['adjustedInsuranceTotal']
            + $supplementsExtraTotal;

        $total = $subtotal - $courseDiscountAmount - $pioneers['cashTotal'];

        return [
            'weeklyCourseFee' => $weeklyCourseFee,
            'courseTotal' => round($courseTotal, 2),
            'weeklyAccFee' => $weeklyAccFee,
            'accTotal' => round($accTotal, 2),
            'accPrice' => round($pioneers['adjustedAccTotal'], 2),
            'accOriginalTotal' => $pioneers['accWaived'] ? round($pioneers['accOriginalTotal'], 2) : round($pioneers['adjustedAccTotal'], 2),
            'accWaived' => $pioneers['accWaived'],
            'oneTimeFees' => $pioneers['adjustedOneTimeFees'],
            'accSupplements' => $accSupplements,
            'insuranceLines' => $pioneers['adjustedInsuranceLines'],
            'insuranceTotal' => round($pioneers['adjustedInsuranceTotal'], 2),
            'supplementLines' => $supplementLines,
            'pickupTotal' => round($pioneers['adjustedPickupTotal'], 2),
            'pickupOriginalTotal' => round($pioneers['pickupOriginalTotal'], 2),
            'pickupWaived' => $pioneers['pickupWaived'],
            'pioneersCashLines' => $pioneers['cashLines'],
            'pioneersFreeLines' => $pioneers['freeLines'],
            'pioneersCashTotal' => round($pioneers['cashTotal'], 2),
            'courseDiscountPercent' => $courseDiscountPercent,
            'courseDiscountAmount' => round($courseDiscountAmount, 2),
            'subtotal' => round($subtotal, 2),
            'total' => round(max(0, $total), 2),
        ];
    }

    /**
     * @param  array<string, float|int|string|null>  $prices
     */
    public function getPriceFromMap(?array $prices, string $currency): ?float
    {
        if (!$prices || !$currency) {
            return null;
        }

        $code = strtoupper($currency);
        if (array_key_exists($code, $prices) && $prices[$code] !== null) {
            return (float) $prices[$code];
        }

        $lower = strtolower($code);
        if (array_key_exists($lower, $prices) && $prices[$lower] !== null) {
            return (float) $prices[$lower];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    public function getItemPrice(array $item, string $currency, ?string $field = null, ?string $pricesKey = null): float
    {
        $code = strtoupper($currency);

        if ($pricesKey && ! empty($item[$pricesKey]) && is_array($item[$pricesKey])) {
            $mapped = $this->getPriceFromMap($item[$pricesKey], $code);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        if (! empty($item['prices']) && is_array($item['prices']) && ! isset($item['prices']['new'])) {
            $mapped = $this->getPriceFromMap($item['prices'], $code);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        if ($field) {
            $fieldPricesKey = $field . '_prices';
            if (! empty($item[$fieldPricesKey]) && is_array($item[$fieldPricesKey])) {
                $mapped = $this->getPriceFromMap($item[$fieldPricesKey], $code);
                if ($mapped !== null) {
                    return $mapped;
                }
            }

            if ($code === 'SAR' && isset($item[$field . '_sar'])) {
                return (float) $item[$field . '_sar'];
            }
            if ($code === 'GBP' && isset($item[$field . '_gbp'])) {
                return (float) $item[$field . '_gbp'];
            }
        }

        if (! empty($item['prices']['new']) && is_array($item['prices']['new'])) {
            $mapped = $this->getPriceFromMap($item['prices']['new'], $code);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        if ($code === 'SAR' && isset($item['price_sar'])) {
            return (float) $item['price_sar'];
        }
        if ($code === 'GBP' && isset($item['price_gbp'])) {
            return (float) $item['price_gbp'];
        }

        $baseCurrency = strtoupper((string) ($item['base_currency'] ?? 'GBP'));
        if ($code === $baseCurrency) {
            if ($field && isset($item[$field])) {
                return (float) $item[$field];
            }
            foreach (['price', 'amount', 'fee_per_week'] as $fallback) {
                if (isset($item[$fallback])) {
                    return (float) $item[$fallback];
                }
            }
        }

        return 0.0;
    }

    /**
     * @param  array<string, mixed>  $course
     */
    public function resolveWeeklyCourseFee(array $course, int $weeks, string $currency): float
    {
        $selectedWeeks = max(1, $weeks);
        $tiers = is_array($course['week_tiers'] ?? null) ? $course['week_tiers'] : [];

        if ($tiers === []) {
            return $this->getItemPrice($course, $currency, 'price_per_week', 'price_per_week_prices')
                ?: $this->getItemPrice($course, $currency, 'price', 'price_per_week_prices');
        }

        $matched = $tiers[0];
        foreach ($tiers as $tier) {
            if ((int) ($tier['from_weeks'] ?? 0) <= $selectedWeeks) {
                $matched = $tier;
            } else {
                break;
            }
        }

        return $this->getPriceFromMap($matched['prices'] ?? null, $currency)
            ?? (float) ($matched['weekly_fee'] ?? 0);
    }

    /**
     * @param  array<string, mixed>  $accommodation
     * @param  array<string, string>  $labels
     * @return list<array<string, mixed>>
     */
    public function getAccommodationSupplements(
        array $accommodation,
        int $weeks,
        mixed $startDate,
        mixed $accAge,
        string $currency,
        array $labels = [],
    ): array {
        $supplements = $accommodation['supplements'] ?? null;
        if (! is_array($supplements)) {
            return [];
        }

        $selectedWeeks = max(1, $weeks);
        $items = [];

        foreach (['summer' => 'Summer Supplement', 'winter' => 'Winter Supplement', 'other' => 'Additional Supplement'] as $key => $fallback) {
            $entry = $supplements[$key] ?? null;
            if (! is_array($entry) || (float) ($entry['fee'] ?? 0) <= 0 || empty($entry['start_date']) || empty($entry['end_date']) || ! $startDate) {
                continue;
            }

            $overlappingWeeks = $this->countOverlappingWeeks($startDate, $selectedWeeks, $entry['start_date'], $entry['end_date']);
            if ($overlappingWeeks <= 0) {
                continue;
            }

            $unit = $this->getPriceFromMap($entry['prices'] ?? null, $currency) ?? (float) ($entry['fee'] ?? 0);
            $perWeek = (bool) ($entry['per_week'] ?? false);
            $items[] = [
                'key' => $key,
                'label' => $key === 'other' && ! empty($entry['name']) ? $entry['name'] : ($labels[$key] ?? $fallback),
                'unit' => $unit,
                'total' => $perWeek ? $unit * $overlappingWeeks : $unit,
                'perWeek' => $perWeek,
                'weeks' => $overlappingWeeks,
            ];
        }

        $under18 = $supplements['under_18'] ?? null;
        $minAge = (int) ($accommodation['min_age'] ?? 0);
        $studentAge = $accAge !== null ? (int) $accAge : null;

        if (
            is_array($under18)
            && (float) ($under18['fee'] ?? 0) > 0
            && $minAge > 0
            && $studentAge !== null
            && $studentAge < $minAge
        ) {
            $unit = $this->getPriceFromMap($under18['prices'] ?? null, $currency) ?? (float) ($under18['fee'] ?? 0);
            $perWeek = (bool) ($under18['per_week'] ?? false);
            $items[] = [
                'key' => 'under_18',
                'label' => $labels['under_18'] ?? 'Under 18 Supplement',
                'unit' => $unit,
                'total' => $perWeek ? $unit * $selectedWeeks : $unit,
                'perWeek' => $perWeek,
                'weeks' => $selectedWeeks,
            ];
        }

        return $items;
    }

    /**
     * @param  array<string, mixed>  $insurance
     * @param  array<string, string>  $labels
     * @return array{lines: list<array<string, mixed>>, total: float, weeklyUnit: float, adminFee: float}
     */
    public function getInsurancePricing(array $insurance, int $weeks, string $currency, array $labels = []): array
    {
        $selectedWeeks = max(1, $weeks);
        $weeklyUnit = $this->getItemPrice($insurance, $currency, 'price', 'prices')
            ?: $this->getItemPrice($insurance, $currency, 'amount', 'prices');
        $adminFee = $this->getItemPrice($insurance, $currency, 'admin_fee', 'admin_fee_prices');

        $lines = [];

        if ($weeklyUnit > 0) {
            $lines[] = [
                'key' => 'insurance_weekly',
                'label' => $labels['insurance'] ?? ($insurance['name'] ?? 'Insurance'),
                'unit' => $weeklyUnit,
                'weeks' => $selectedWeeks,
                'perWeek' => true,
                'total' => $weeklyUnit * $selectedWeeks,
            ];
        }

        if ($adminFee > 0) {
            $lines[] = [
                'key' => 'insurance_admin',
                'label' => $labels['insurance_admin'] ?? 'Insurance Admin Fee',
                'total' => $adminFee,
                'perWeek' => false,
            ];
        }

        return [
            'lines' => $lines,
            'total' => array_sum(array_column($lines, 'total')),
            'weeklyUnit' => $weeklyUnit,
            'adminFee' => $adminFee,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $discounts
     * @param  array<string, mixed>|null  $selectedCourse
     */
    public function resolveCoursePromotionPercent(mixed $selectedCourseId, array $discounts, ?array $selectedCourse): float
    {
        $coursePromos = array_values(array_filter(
            $discounts,
            fn (array $entry) => empty($entry['course_id'])
                || (int) ($entry['course_id'] ?? 0) === (int) $selectedCourseId,
        ));

        if ($coursePromos !== []) {
            return (float) max(array_map(fn (array $entry) => (float) ($entry['discount_percentage'] ?? 0), $coursePromos));
        }

        return (float) ($selectedCourse['discount_percent']
            ?? $selectedCourse['promotion_percentage']
            ?? 0);
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function applyPioneersBenefits(array $params): array
    {
        $qualifying = $this->resolveQualifyingPioneersDiscounts(
            (int) $params['selectedWeeks'],
            $params['pioneersDiscounts'] ?? [],
            (string) $params['currency'],
        );

        $waivedKeys = [];
        $cashLines = [];
        $freeLines = [];
        $cashTotal = 0.0;

        foreach ($qualifying as $tier) {
            if (($tier['type'] ?? '') === 'free_item') {
                $waivedKeys[] = $tier['freeFor'];
                $freeLines[] = [
                    'key' => 'pioneers_free_' . ($tier['id'] ?? ''),
                    'id' => $tier['id'] ?? null,
                    'label' => $tier['name'] ?? '',
                    'ar_label' => $tier['ar_name'] ?? null,
                    'freeFor' => $tier['freeFor'],
                    'tierWeeks' => $tier['tierWeeks'],
                    'type' => 'free_item',
                ];
                continue;
            }

            if (($tier['total'] ?? 0) > 0) {
                $cashLines[] = [
                    'key' => 'pioneers_cash_' . ($tier['id'] ?? ''),
                    'id' => $tier['id'] ?? null,
                    'label' => $tier['name'] ?? '',
                    'ar_label' => $tier['ar_name'] ?? null,
                    'type' => 'cash',
                    'tierWeeks' => $tier['tierWeeks'],
                    'multiplier' => $tier['multiplier'],
                    'unitAmount' => $tier['unitAmount'],
                    'total' => $tier['total'],
                ];
                $cashTotal += (float) $tier['total'];
            }
        }

        $waivedSet = array_flip($waivedKeys);
        $oneTimeFees = $params['oneTimeFees'] ?? [];
        $insuranceLines = $params['insuranceLines'] ?? [];

        $adjustedOneTimeFees = array_map(function (array $fee) use ($waivedSet) {
            if (($fee['key'] ?? '') === 'registration' && isset($waivedSet['registration'])) {
                return array_merge($fee, ['originalTotal' => $fee['total'], 'total' => 0, 'waived' => true]);
            }
            if (($fee['key'] ?? '') === 'material_books' && isset($waivedSet['material_books'])) {
                return array_merge($fee, ['originalTotal' => $fee['total'], 'total' => 0, 'waived' => true]);
            }

            return $fee;
        }, $oneTimeFees);

        $adjustedInsuranceLines = array_map(function (array $line) use ($waivedSet) {
            if (isset($waivedSet['insurance'])) {
                return array_merge($line, ['originalTotal' => $line['total'], 'total' => 0, 'waived' => true]);
            }

            return $line;
        }, $insuranceLines);

        $pickupTotal = (float) ($params['pickupTotal'] ?? 0);
        $insuranceTotal = (float) ($params['insuranceTotal'] ?? 0);
        $accTotal = (float) ($params['accTotal'] ?? 0);

        return [
            'qualifying' => $qualifying,
            'cashLines' => $cashLines,
            'freeLines' => $freeLines,
            'cashTotal' => $cashTotal,
            'adjustedPickupTotal' => isset($waivedSet['pickup']) ? 0.0 : $pickupTotal,
            'pickupWaived' => isset($waivedSet['pickup']),
            'pickupOriginalTotal' => $pickupTotal,
            'adjustedInsuranceTotal' => isset($waivedSet['insurance']) ? 0.0 : $insuranceTotal,
            'adjustedInsuranceLines' => $adjustedInsuranceLines,
            'adjustedAccTotal' => isset($waivedSet['accommodation']) ? 0.0 : $accTotal,
            'accWaived' => isset($waivedSet['accommodation']),
            'accOriginalTotal' => $accTotal,
            'adjustedOneTimeFees' => $adjustedOneTimeFees,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $tiers
     * @return list<array<string, mixed>>
     */
    private function resolveQualifyingPioneersDiscounts(int $weeks, array $tiers, string $currency): array
    {
        $selectedWeeks = max(1, $weeks);
        $qualifying = [];

        foreach ($tiers as $tier) {
            if ($selectedWeeks < (int) ($tier['weeks'] ?? 0)) {
                continue;
            }

            $tierWeeks = max(1, (int) ($tier['weeks'] ?? 1));
            $multiplier = intdiv($selectedWeeks, $tierWeeks);
            $freeFor = $this->normalizePioneersFreeFor($tier['discount_full_for'] ?? null);

            if ($freeFor) {
                $qualifying[] = [
                    'id' => $tier['id'] ?? null,
                    'name' => $tier['name'] ?? '',
                    'ar_name' => $tier['ar_name'] ?? null,
                    'type' => 'free_item',
                    'freeFor' => $freeFor,
                    'tierWeeks' => $tierWeeks,
                    'multiplier' => 1,
                    'unitAmount' => 0,
                    'total' => 0,
                ];
                continue;
            }

            $unitAmount = $this->getPriceFromMap($tier['discount_amount_prices'] ?? null, $currency)
                ?? (float) ($tier['discount_amount'] ?? 0);

            $qualifying[] = [
                'id' => $tier['id'] ?? null,
                'name' => $tier['name'] ?? '',
                'ar_name' => $tier['ar_name'] ?? null,
                'type' => 'cash',
                'tierWeeks' => $tierWeeks,
                'multiplier' => $multiplier,
                'unitAmount' => $unitAmount,
                'total' => $unitAmount * $multiplier,
            ];
        }

        return $qualifying;
    }

    private function normalizePioneersFreeFor(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $key = strtolower(trim((string) $value));

        return self::FREE_FOR_ALIASES[$key] ?? $key;
    }

    public function countOverlappingWeeks(mixed $startDate, int $weeks, mixed $rangeStart, mixed $rangeEnd): int
    {
        $stayStart = $this->parseApiDate($startDate);
        $rangeFrom = $this->parseApiDate($rangeStart);
        $rangeTo = $this->parseApiDate($rangeEnd);

        if (! $stayStart || ! $rangeFrom || ! $rangeTo) {
            return 0;
        }

        $totalWeeks = max(1, $weeks);
        $count = 0;

        for ($i = 0; $i < $totalWeeks; $i++) {
            $weekStart = clone $stayStart;
            $weekStart->modify('+' . ($i * 7) . ' days');
            $weekStart->setTime(0, 0, 0);

            $weekEndExclusive = clone $weekStart;
            $weekEndExclusive->modify('+7 days');

            $rangeFromDay = clone $rangeFrom;
            $rangeFromDay->setTime(0, 0, 0);
            $rangeToDay = clone $rangeTo;
            $rangeToDay->setTime(23, 59, 59);

            if ($weekStart <= $rangeToDay && $weekEndExclusive > $rangeFromDay) {
                $count++;
            }
        }

        return $count;
    }

    private function parseApiDate(mixed $value): ?\DateTimeImmutable
    {
        if ($value instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromInterface($value);
        }

        if (! $value) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', (string) $value);

        return $date ?: null;
    }
}
