<?php

namespace App\Support;

use App\Models\BankAccount;
use App\Models\OnlineCourseBooking;

class OnlineCourseBookingPresenter
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
    ) {
    }

    public function summary(OnlineCourseBooking $booking): ?array
    {
        $detail = $this->detail($booking);
        if (! $detail) {
            return null;
        }

        unset($detail['fee_lines'], $detail['payment_banks']);

        return $detail;
    }

    public function detail(OnlineCourseBooking $booking): ?array
    {
        $booking->loadMissing(['school', 'course.courseType']);

        if (! $booking->course) {
            return null;
        }

        $courseCard = $this->support->onlineCourseCard($booking->course);
        $school = $booking->school;
        $currency = strtoupper((string) $booking->display_currency);
        $pricing = is_array($booking->pricing_snapshot) ? $booking->pricing_snapshot : [];

        $feeLines = [];
        if ((float) $booking->course_fee > 0) {
            $feeLines[] = [
                'key' => 'course',
                'label' => $courseCard['name'] ?? 'Online Course',
                'ar_label' => $courseCard['ar_name'] ?? 'دورة أونلاين',
                'amount' => (float) $booking->course_fee,
            ];
        }
        if ((float) $booking->registration_fee > 0) {
            $feeLines[] = [
                'key' => 'registration',
                'label' => 'Registration Fee',
                'ar_label' => 'رسوم التسجيل',
                'amount' => (float) $booking->registration_fee,
            ];
        }

        return [
            'id' => $booking->id,
            'booking_type' => 'online_course',
            'course_type' => 'online_courses',
            'reference_no' => $booking->reference_no,
            'booking_id' => $booking->reference_no,
            'status' => $booking->status,
            'source' => $booking->source,
            'weeks' => $booking->weeks,
            'start_date' => optional($booking->start_date)->format('Y-m-d'),
            'currency' => $currency,
            'display_currency' => $currency,
            'final_price' => (float) $booking->total_amount,
            'total' => (float) $booking->total_amount,
            'total_discount' => 0,
            'fee_lines' => $feeLines,
            'discount_lines' => [],
            'school_name' => $school?->name_en,
            'school_ar_name' => $school?->name_ar,
            'school_slug' => $school?->slug,
            'course_name' => $courseCard['name'] ?? null,
            'course_ar_name' => $courseCard['ar_name'] ?? null,
            'course_type_name' => $courseCard['course_type'] ?? null,
            'course_type_ar_name' => $courseCard['course_type_ar_name'] ?? null,
            'contact_whatsapp' => $booking->contact_whatsapp ?: $booking->contact_phone,
            'student_phone' => $booking->contact_phone,
            'image' => $courseCard['image'] ?? null,
            'logo' => $this->support->toPublicUrl($school?->logo_url),
            'country_name' => $courseCard['country_name'] ?? null,
            'country_ar_name' => $courseCard['country_ar_name'] ?? null,
            'city_name' => $courseCard['city_name'] ?? null,
            'city_ar_name' => $courseCard['city_ar_name'] ?? null,
            'services' => [],
            'payment_banks' => $this->paymentBanks(),
            'pricing' => $pricing,
            'created_at' => optional($booking->created_at)->toIso8601String(),
        ];
    }

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
