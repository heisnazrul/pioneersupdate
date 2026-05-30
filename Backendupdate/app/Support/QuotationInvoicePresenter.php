<?php

namespace App\Support;

use App\Models\BankAccount;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolPickup;
use App\Models\Quotation;

class QuotationInvoicePresenter
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
    ) {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function detail(Quotation $quotation): ?array
    {
        $quotation->loadMissing(['student', 'school']);

        return $quotation->booking_type === 'online_course'
            ? $this->onlineDetail($quotation)
            : $this->languageDetail($quotation);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function languageDetail(Quotation $quotation): ?array
    {
        $selection = is_array($quotation->selection_snapshot) ? $quotation->selection_snapshot : [];
        $pricing = is_array($quotation->pricing_snapshot) ? $quotation->pricing_snapshot : [];
        $currency = strtoupper((string) ($quotation->display_currency ?? 'SAR'));
        $weeks = max(1, (int) ($selection['weeks'] ?? 1));

        $course = $quotation->course_id
            ? LanguageSchoolCourse::query()->find($quotation->course_id)
            : null;

        $accommodation = null;
        $accId = $selection['accommodation_id'] ?? null;
        if ($accId && $accId !== 'no-acc') {
            $accommodation = LanguageSchoolAccommodation::query()->find((int) $accId);
        }

        $pickup = ! empty($selection['pickup_id'])
            ? LanguageSchoolPickup::query()->find((int) $selection['pickup_id'])
            : null;

        $school = $quotation->school;
        $coursePayload = $course
            ? ($this->support->coursePayload('language_courses', $course) ?? [])
            : [];

        $feeLines = $this->buildLanguageFeeLines($pricing, $weeks, $course, $accommodation, $pickup);
        $discountLines = $this->buildDiscountLines($pricing);

        return [
            'reference_no' => $quotation->reference_no,
            'school_name' => $coursePayload['school_name'] ?? $school?->name_en,
            'course_name' => $coursePayload['name'] ?? $course?->course_name_from_school,
            'start_date' => $selection['start_date'] ?? null,
            'weeks' => $weeks,
            'final_price' => (float) $quotation->total_amount,
            'total' => (float) ($pricing['total'] ?? $quotation->total_amount),
            'total_discount' => $this->totalDiscount($pricing),
            'currency' => $currency,
            'school_logo' => $coursePayload['logo'] ?? $this->support->toPublicUrl($school?->logo_url),
            'course_image' => $coursePayload['image'] ?? null,
            'fee_lines' => $feeLines,
            'discount_lines' => $discountLines,
            'payment_banks' => $this->paymentBanks(),
            'valid_until' => optional($quotation->valid_until)->format('Y-m-d'),
            'student_name' => $quotation->student?->name,
            'student_email' => $quotation->student?->email,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function onlineDetail(Quotation $quotation): array
    {
        $selection = is_array($quotation->selection_snapshot) ? $quotation->selection_snapshot : [];
        $pricing = is_array($quotation->pricing_snapshot) ? $quotation->pricing_snapshot : [];
        $currency = strtoupper((string) ($quotation->display_currency ?? 'SAR'));
        $weeks = max(1, (int) ($selection['weeks'] ?? 1));

        $course = $quotation->course_id
            ? LanguageOnlineCourse::query()->find($quotation->course_id)
            : null;

        $school = $quotation->school;
        $feeLines = [];
        $courseTotal = (float) ($pricing['courseTotal'] ?? $pricing['course_fee'] ?? 0);
        if ($courseTotal > 0) {
            $feeLines[] = [
                'key' => 'course',
                'label' => $course?->name_en ?? $course?->name ?? 'Online Course',
                'detail' => $weeks > 1 ? "{$weeks} weeks" : null,
                'amount' => $courseTotal,
            ];
        }

        $registrationFee = (float) ($pricing['registration_fee'] ?? 0);
        if ($registrationFee > 0) {
            $feeLines[] = [
                'key' => 'registration',
                'label' => 'Registration Fee',
                'amount' => $registrationFee,
            ];
        }

        return [
            'reference_no' => $quotation->reference_no,
            'school_name' => $school?->name_en,
            'course_name' => $course?->name_en ?? $course?->name,
            'start_date' => $selection['start_date'] ?? null,
            'weeks' => $weeks,
            'final_price' => (float) $quotation->total_amount,
            'total' => (float) ($pricing['total'] ?? $quotation->total_amount),
            'total_discount' => 0,
            'currency' => $currency,
            'school_logo' => $this->support->toPublicUrl($school?->logo_url),
            'fee_lines' => $feeLines,
            'discount_lines' => [],
            'payment_banks' => $this->paymentBanks(),
            'valid_until' => optional($quotation->valid_until)->format('Y-m-d'),
            'student_name' => $quotation->student?->name,
            'student_email' => $quotation->student?->email,
        ];
    }

    /**
     * @param  array<string, mixed>  $pricing
     * @return list<array<string, mixed>>
     */
    private function buildLanguageFeeLines(
        array $pricing,
        int $weeks,
        ?LanguageSchoolCourse $course,
        ?LanguageSchoolAccommodation $accommodation,
        ?LanguageSchoolPickup $pickup,
    ): array {
        $lines = [];

        $courseTotal = (float) ($pricing['courseTotal'] ?? 0);
        if ($courseTotal > 0) {
            $lines[] = [
                'key' => 'course',
                'label' => $course?->course_name_from_school ?: 'General English Course',
                'detail' => "{$weeks} weeks",
                'amount' => $courseTotal,
            ];
        }

        $accTotal = (float) ($pricing['accPrice'] ?? 0);
        if ($accTotal > 0) {
            $lines[] = [
                'key' => 'accommodation',
                'label' => $accommodation?->name ?: 'Homestay Accommodation',
                'detail' => "{$weeks} weeks",
                'amount' => $accTotal,
            ];
        }

        foreach ($pricing['oneTimeFees'] ?? [] as $fee) {
            $total = (float) ($fee['total'] ?? 0);
            if ($total <= 0) {
                continue;
            }
            $lines[] = [
                'key' => $fee['key'] ?? 'fee',
                'label' => $fee['label'] ?? 'Fee',
                'amount' => $total,
            ];
        }

        if (($pricing['pickupTotal'] ?? 0) > 0 && ! ($pricing['pickupWaived'] ?? false)) {
            $lines[] = [
                'key' => 'pickup',
                'label' => 'Airport Pickup',
                'detail' => $pickup?->pickup_location,
                'amount' => (float) $pricing['pickupTotal'],
            ];
        }

        foreach ($pricing['insuranceLines'] ?? [] as $line) {
            $total = (float) ($line['total'] ?? 0);
            if ($total <= 0) {
                continue;
            }
            $lines[] = [
                'key' => $line['key'] ?? 'insurance',
                'label' => $line['label'] ?? 'Insurance',
                'amount' => $total,
            ];
        }

        foreach ($pricing['accSupplements'] ?? [] as $line) {
            $total = (float) ($line['total'] ?? 0);
            if ($total <= 0) {
                continue;
            }
            $lines[] = [
                'key' => $line['key'] ?? 'acc_supplement',
                'label' => $line['label'] ?? 'Supplement',
                'amount' => $total,
            ];
        }

        foreach ($pricing['supplementLines'] ?? [] as $line) {
            $total = (float) ($line['total'] ?? 0);
            if ($total <= 0) {
                continue;
            }
            $lines[] = [
                'key' => $line['key'] ?? 'supplement',
                'label' => $line['label'] ?? 'Supplement',
                'amount' => $total,
            ];
        }

        return $lines;
    }

    /**
     * @param  array<string, mixed>  $pricing
     * @return list<array<string, mixed>>
     */
    private function buildDiscountLines(array $pricing): array
    {
        $lines = [];

        $courseDiscount = (float) ($pricing['courseDiscountAmount'] ?? 0);
        if ($courseDiscount > 0) {
            $lines[] = [
                'key' => 'course_discount',
                'label' => 'English Course Discount',
                'amount' => -1 * $courseDiscount,
            ];
        }

        foreach ($pricing['pioneersCashLines'] ?? [] as $line) {
            $total = (float) ($line['total'] ?? 0);
            if ($total <= 0) {
                continue;
            }
            $lines[] = [
                'key' => 'pioneers_' . ($line['key'] ?? 'cash'),
                'label' => $line['label'] ?? 'Pioneers Discount',
                'amount' => -1 * $total,
            ];
        }

        $totalDiscount = $this->totalDiscount($pricing);
        if ($totalDiscount > 0 && count($lines) > 1) {
            $lines[] = [
                'key' => 'total_discount',
                'label' => 'Total Discount',
                'amount' => -1 * $totalDiscount,
                'is_summary' => true,
            ];
        }

        return $lines;
    }

    /**
     * @param  array<string, mixed>  $pricing
     */
    private function totalDiscount(array $pricing): float
    {
        return round(
            (float) ($pricing['courseDiscountAmount'] ?? 0)
            + (float) ($pricing['pioneersCashTotal'] ?? 0),
            2,
        );
    }

    /**
     * @return list<array<string, string>>
     */
    private function paymentBanks(): array
    {
        return BankAccount::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name_en')
            ->get()
            ->map(fn (BankAccount $account) => [
                'name' => $account->name_en,
                'logo_text' => $account->logo_text ?: $account->name_en,
                'logo_url' => $this->support->toPublicUrl($account->logo_path),
                'beneficiary' => $account->beneficiary_en,
                'account_number' => $account->account_number,
                'iban' => $account->iban,
            ])
            ->values()
            ->all();
    }
}
