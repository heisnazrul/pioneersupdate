<?php

namespace App\Support;

use App\Models\BankAccount;
use App\Models\LanguageCourseBooking;
use App\Models\LanguageSchoolInsurance;

class LanguageCourseBookingPresenter
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
    ) {
    }

    public function summary(LanguageCourseBooking $booking): ?array
    {
        $detail = $this->detail($booking);

        if (! $detail) {
            return null;
        }

        unset($detail['pricing'], $detail['services'], $detail['payment_banks']);

        return $detail;
    }

    public function detail(LanguageCourseBooking $booking): ?array
    {
        $booking->loadMissing([
            'school',
            'course.branch.city.country',
            'accommodation.type',
            'accommodation.bedroomType',
            'accommodation.bathroomType',
            'accommodation.mealPlan',
            'pickup',
        ]);

        $coursePayload = $this->support
            ->coursePayload('language_courses', $booking->course) ?? [];

        if (! $coursePayload && ! $booking->course) {
            return null;
        }

        $school = $booking->school;
        $currency = strtoupper((string) $booking->display_currency);
        $pricing = is_array($booking->pricing_snapshot) ? $booking->pricing_snapshot : [];
        $selection = is_array($booking->selection_snapshot) ? $booking->selection_snapshot : [];

        $accommodation = null;
        if ($booking->accommodation) {
            $accCard = $this->support->accommodationCard($booking->accommodation);
            $accommodation = [
                'id' => $accCard['id'] ?? null,
                'name' => $accCard['name'] ?? null,
                'ar_name' => $accCard['ar_name'] ?? null,
                'type' => $accCard['type'] ?? null,
                'type_ar' => $accCard['type_ar'] ?? null,
                'bedroom_type' => $accCard['bedroom_type'] ?? null,
                'bedroom_type_ar' => $accCard['bedroom_type_ar'] ?? null,
                'bathroom_type' => $accCard['bathroom_type'] ?? null,
                'bathroom_type_ar' => $accCard['bathroom_type_ar'] ?? null,
                'meal_plan' => $accCard['meal_plan'] ?? null,
                'meal_plan_ar' => $accCard['meal_plan_ar'] ?? null,
                'features' => $accCard['features'] ?? [],
                'features_ar' => array_values(array_filter([
                    $accCard['bedroom_type_ar'] ?? $accCard['bedroom_type'] ?? null,
                    $accCard['bathroom_type_ar'] ?? $accCard['bathroom_type'] ?? null,
                    $accCard['meal_plan_ar'] ?? $accCard['meal_plan'] ?? null,
                ])),
                'total' => (float) ($pricing['accPrice'] ?? $booking->accommodation_total ?? 0),
            ];
        }

        $pickup = null;
        if ($booking->pickup) {
            $pickupCard = $this->support->pickupCard($booking->pickup);
            $pickup = [
                'id' => $pickupCard['id'] ?? null,
                'name' => $pickupCard['name'] ?? null,
                'ar_name' => $pickupCard['ar_name'] ?? null,
                'total' => (float) ($pricing['pickupTotal'] ?? $booking->pickup_fee ?? 0),
                'waived' => (bool) $booking->pickup_waived,
            ];
        }

        $insurances = [];
        $insuranceIds = array_values(array_map('intval', $booking->insurance_ids ?? []));
        if ($insuranceIds) {
            LanguageSchoolInsurance::query()
                ->whereIn('id', $insuranceIds)
                ->get()
                ->each(function (LanguageSchoolInsurance $insurance) use (&$insurances) {
                    $card = $this->support->insuranceCard($insurance);
                    $insurances[] = [
                        'id' => $card['id'] ?? null,
                        'name' => $card['name'] ?? 'Insurance',
                        'ar_name' => $card['ar_name'] ?? 'التأمين',
                    ];
                });
        }

        $services = [];
        if ($pickup) {
            $services[] = [
                'key' => 'pickup',
                'name' => 'Airport Pickup',
                'ar_name' => 'الاستقبال من المطار',
                'detail' => $pickup['name'],
                'ar_detail' => $pickup['ar_name'] ?? $pickup['name'],
            ];
        }
        foreach ($insurances as $insurance) {
            $services[] = [
                'key' => 'insurance_' . ($insurance['id'] ?? ''),
                'name' => $insurance['name'] ?? 'Insurance',
                'ar_name' => $insurance['ar_name'] ?? 'التأمين',
                'detail' => null,
                'ar_detail' => null,
            ];
        }

        $feeLines = $this->buildFeeLines($booking, $pricing, $currency);

        return [
            'id' => $booking->id,
            'booking_id' => $booking->reference_no,
            'reference_no' => $booking->reference_no,
            'status' => $booking->status,
            'course_type' => 'language_courses',
            'school_name' => $coursePayload['school_name'] ?? $school?->name_en,
            'school_ar_name' => $coursePayload['school_ar_name'] ?? $school?->name_ar,
            'course_name' => $coursePayload['name'] ?? $booking->course?->course_name_from_school,
            'course_ar_name' => $coursePayload['ar_name'] ?? $booking->course?->course_name_from_school_ar,
            'course_type_name' => $coursePayload['course_type'] ?? null,
            'course_type_ar_name' => $coursePayload['course_type_ar'] ?? null,
            'country_name' => $coursePayload['country_name'] ?? null,
            'country_ar_name' => $coursePayload['country_ar_name'] ?? null,
            'country_flag' => $coursePayload['flag'] ?? null,
            'city_name' => $coursePayload['city_name'] ?? null,
            'city_ar_name' => $coursePayload['city_ar_name'] ?? null,
            'start_date' => optional($booking->start_date)->format('Y-m-d'),
            'weeks' => $booking->weeks,
            'final_price' => (float) $booking->total_amount,
            'original_price' => (float) $booking->subtotal,
            'subtotal' => (float) $booking->subtotal,
            'total' => (float) $booking->total_amount,
            'total_discount' => round(
                (float) ($booking->course_discount_amount ?? 0)
                + (float) ($booking->pioneers_discount_total ?? 0)
                + (float) ($booking->coupon_discount_amount ?? 0)
                + (float) ($booking->referral_discount_amount ?? 0),
                2,
            ),
            'currency' => $currency,
            'rating' => $coursePayload['rating'] ?? 4,
            'course_image' => $coursePayload['image'] ?? ($coursePayload['logo'] ?? null),
            'school_logo' => $coursePayload['logo'] ?? $this->support->toPublicUrl($school?->logo_url),
            'created_at' => optional($booking->created_at)->toIso8601String(),
            'student_name' => $booking->contact_name,
            'student_email' => $booking->contact_email,
            'student_phone' => $booking->contact_phone,
            'contact_whatsapp' => $booking->contact_whatsapp ?: $booking->contact_phone,
            'accommodation' => $accommodation,
            'pickup' => $pickup,
            'insurances' => $insurances,
            'services' => $services,
            'selection' => $selection,
            'pricing' => $pricing,
            'fee_lines' => $feeLines,
            'discount_lines' => $this->buildDiscountLines($booking, $pricing),
            'payment_banks' => $this->paymentBanks(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildFeeLines(LanguageCourseBooking $booking, array $pricing, string $currency): array
    {
        $weeks = (int) $booking->weeks;
        $lines = [];

        $courseName = $booking->course?->course_name_from_school;
        $courseArName = $booking->course?->course_name_from_school_ar;

        $courseTotal = (float) ($pricing['courseTotal'] ?? $booking->course_total ?? 0);
        if ($courseTotal > 0) {
            $lines[] = [
                'key' => 'course',
                'label' => $courseName ?: 'General English Course',
                'ar_label' => $courseArName ?: 'دورة لغة إنجليزية عامة',
                'detail' => "{$weeks} weeks",
                'ar_detail' => "{$weeks} أسبوع",
                'amount' => $courseTotal,
            ];
        }

        $accTotal = (float) ($pricing['accPrice'] ?? $booking->accommodation_total ?? 0);
        if ($accTotal > 0) {
            $accName = $booking->accommodation?->name;
            $lines[] = [
                'key' => 'accommodation',
                'label' => $accName ?: 'Homestay Accommodation',
                'ar_label' => $accName ?: 'إقامة مع عائلة',
                'detail' => "{$weeks} weeks",
                'ar_detail' => "{$weeks} أسبوع",
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
                'ar_label' => $fee['ar_label'] ?? ($fee['label'] ?? 'Fee'),
                'detail' => null,
                'ar_detail' => null,
                'amount' => $total,
            ];
        }

        if (($pricing['pickupTotal'] ?? 0) > 0 && ! ($pricing['pickupWaived'] ?? false)) {
            $lines[] = [
                'key' => 'pickup',
                'label' => 'Airport Pickup',
                'ar_label' => 'الاستقبال من المطار',
                'detail' => $booking->pickup?->pickup_location,
                'ar_detail' => $booking->pickup?->pickup_location,
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
                'ar_label' => $line['ar_label'] ?? ($line['label'] ?? 'Insurance'),
                'detail' => null,
                'ar_detail' => null,
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
                'ar_label' => $line['ar_label'] ?? ($line['label'] ?? 'Supplement'),
                'detail' => null,
                'ar_detail' => null,
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
                'ar_label' => $line['ar_label'] ?? ($line['label'] ?? 'Supplement'),
                'detail' => null,
                'ar_detail' => null,
                'amount' => $total,
            ];
        }

        return $lines;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildDiscountLines(LanguageCourseBooking $booking, array $pricing): array
    {
        $lines = [];

        $courseDiscount = (float) ($pricing['courseDiscountAmount'] ?? $booking->course_discount_amount ?? 0);
        if ($courseDiscount > 0) {
            $lines[] = [
                'key' => 'course_discount',
                'label' => 'English Course Discount',
                'ar_label' => 'خصم كورس انجليزي',
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
                'ar_label' => $line['ar_label'] ?? 'خصم Pioneers',
                'amount' => -1 * $total,
            ];
        }

        $totalDiscount = round(
            (float) ($booking->course_discount_amount ?? 0)
            + (float) ($booking->pioneers_discount_total ?? 0),
            2,
        );

        if ($totalDiscount > 0 && count($lines) > 1) {
            $lines[] = [
                'key' => 'total_discount',
                'label' => 'Total Discount',
                'ar_label' => 'اجمالي الخصم',
                'amount' => -1 * $totalDiscount,
                'is_summary' => true,
            ];
        }

        return $lines;
    }

    public function schoolDetail(LanguageCourseBooking $booking): ?array
    {
        $detail = $this->detail($booking);
        if (! $detail) {
            return null;
        }

        $pricing = is_array($booking->pricing_snapshot) ? $booking->pricing_snapshot : [];
        $detail['fee_lines'] = $this->buildSchoolFeeLines($booking, $pricing);
        $detail['discount_lines'] = $this->buildSchoolDiscountLines($booking);
        $schoolTotal = round(array_sum(array_column($detail['fee_lines'], 'amount'))
            + array_sum(array_column($detail['discount_lines'], 'amount')), 2);
        $detail['final_price'] = max(0, $schoolTotal);
        $detail['total'] = $detail['final_price'];
        $detail['total_discount'] = abs((float) ($booking->course_discount_amount ?? 0));
        $detail['copy_label'] = 'School Copy';
        $detail['payment_banks'] = [];

        return $detail;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildSchoolFeeLines(LanguageCourseBooking $booking, array $pricing): array
    {
        $weeks = (int) $booking->weeks;
        $lines = [];

        if ((float) $booking->course_total > 0) {
            $lines[] = [
                'label' => $booking->course?->course_name_from_school ?: 'Course',
                'detail' => "{$weeks} weeks",
                'amount' => (float) $booking->course_total,
            ];
        }

        $accAmount = (float) ($booking->accommodation_waived
            ? ($booking->accommodation_original ?? $pricing['accOriginalTotal'] ?? 0)
            : ($booking->accommodation_total ?? 0));
        if ($accAmount > 0) {
            $lines[] = [
                'label' => $booking->accommodation?->name ?: 'Accommodation',
                'detail' => "{$weeks} weeks",
                'amount' => $accAmount,
            ];
        }

        foreach (['registration_fee' => 'Registration Fee', 'material_fee' => 'Material / Books Fee', 'mandatory_fee' => 'Mandatory Fee'] as $field => $label) {
            $amount = (float) ($booking->{$field} ?? 0);
            if ($amount > 0) {
                $lines[] = ['label' => $label, 'detail' => null, 'amount' => $amount];
            }
        }

        $pickupAmount = (float) ($booking->pickup_waived
            ? ($booking->pickup_original ?? $pricing['pickupOriginalTotal'] ?? 0)
            : ($booking->pickup_fee ?? 0));
        if ($pickupAmount > 0) {
            $lines[] = [
                'label' => 'Airport Pickup',
                'detail' => $booking->pickup?->pickup_location,
                'amount' => $pickupAmount,
            ];
        }

        $insuranceTotal = (float) ($booking->insurance_total ?? 0) + (float) ($booking->insurance_admin_fee ?? 0);
        if ($insuranceTotal > 0) {
            $lines[] = ['label' => 'Insurance', 'detail' => null, 'amount' => $insuranceTotal];
        }

        if ((float) $booking->acc_supplements_total > 0) {
            $lines[] = ['label' => 'Accommodation supplements', 'detail' => null, 'amount' => (float) $booking->acc_supplements_total];
        }

        if ((float) $booking->other_supplements_total > 0) {
            $lines[] = ['label' => 'Other supplements', 'detail' => null, 'amount' => (float) $booking->other_supplements_total];
        }

        return $lines;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildSchoolDiscountLines(LanguageCourseBooking $booking): array
    {
        $lines = [];
        $courseDiscount = (float) ($booking->course_discount_amount ?? 0);
        if ($courseDiscount > 0) {
            $lines[] = [
                'label' => 'Course promotion discount',
                'amount' => -1 * $courseDiscount,
            ];
        }

        return $lines;
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
                'ar_name' => $account->name_ar,
                'logo_text' => $account->logo_text ?: $account->name_en,
                'logo_url' => $this->support->toPublicUrl($account->logo_path),
                'beneficiary' => $account->beneficiary_en,
                'ar_beneficiary' => $account->beneficiary_ar,
                'account_number' => $account->account_number,
                'iban' => $account->iban,
            ])
            ->values()
            ->all();
    }
}
