<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Support\CourseEnglishApiSupport;
use App\Support\QuotationInvoicePresenter;
use Illuminate\Support\Facades\Storage;

class BookingInvoiceViewData
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
    ) {
    }

    /**
     * @param  array<string, mixed>  $detail
     * @return array<string, mixed>
     */
    public function fromDetail(array $detail, LanguageCourseBooking|OnlineCourseBooking $booking, string $copy = 'student'): array
    {
        return $this->fromPayload(array_merge($detail, [
            'reference_no' => $detail['reference_no'] ?? $booking->reference_no,
            'school_name' => $detail['school_name'] ?? $booking->school?->name_en,
            'start_date' => $detail['start_date'] ?? optional($booking->start_date)->format('Y-m-d'),
            'currency' => $detail['currency'] ?? $booking->display_currency ?? 'SAR',
            'total' => $detail['final_price'] ?? $detail['total'] ?? $booking->total_amount,
        ]), $copy);
    }

    public function fromQuotation(Quotation $quotation): array
    {
        $detail = app(QuotationInvoicePresenter::class)->detail($quotation) ?? [];

        return $this->fromPayload($detail, 'student', [
            'copy_label' => 'Student Copy',
            'reference_label' => 'Quotation #',
            'document_title' => 'View invoice',
            'show_contact' => false,
        ]);
    }

    /**
     * @param  array<string, mixed>  $detail
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public function fromPayload(array $detail, string $copy = 'student', array $overrides = []): array
    {
        $currency = strtoupper((string) ($detail['currency'] ?? 'SAR'));
        $feeLines = $detail['fee_lines'] ?? [];
        $discountLines = $detail['discount_lines'] ?? [];
        $totalDiscount = (float) ($detail['total_discount'] ?? 0);
        $total = (float) ($detail['final_price'] ?? $detail['total'] ?? 0);

        $schoolLogo = $this->resolveImagePath($detail['school_logo'] ?? $detail['logo'] ?? null);
        $courseImage = $this->resolveImagePath($detail['course_image'] ?? $detail['image'] ?? null);

        $banks = [];
        if ($copy === 'student') {
            foreach ($detail['payment_banks'] ?? [] as $bank) {
                $banks[] = [
                    'name' => $bank['name'] ?? '',
                    'beneficiary' => $bank['beneficiary'] ?? '',
                    'account_number' => $bank['account_number'] ?? '',
                    'iban' => $bank['iban'] ?? '',
                    'logo_path' => $this->resolveImagePath($bank['logo_url'] ?? null),
                    'logo_text' => $bank['logo_text'] ?? ($bank['name'] ?? ''),
                ];
            }
        }

        return array_merge([
            'copy_label' => $copy === 'school'
                ? ($detail['copy_label'] ?? 'School Copy')
                : 'Student Copy',
            'reference_no' => $detail['reference_no'] ?? '',
            'reference_label' => 'Booking #',
            'document_title' => 'View invoice',
            'school_name' => $detail['school_name'] ?? '',
            'course_name' => $detail['course_name'] ?? null,
            'start_date' => $detail['start_date'] ?? null,
            'valid_until' => $detail['valid_until'] ?? null,
            'currency' => $currency,
            'total' => $total,
            'total_discount' => $totalDiscount,
            'fee_lines' => $feeLines,
            'discount_lines' => $discountLines,
            'banks' => $banks,
            'school_logo_path' => $schoolLogo ?: $courseImage,
            'brand_label' => 'Pioneers Edu',
            'show_payment' => $copy === 'student' && count($banks) > 0,
            'show_contact' => $copy === 'student',
        ], $overrides);
    }

    private function resolveImagePath(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $path = $url;
        if (str_contains($path, '/storage/')) {
            $path = substr($path, strpos($path, '/storage/') + strlen('/storage/'));
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->path($path);
        }

        if (is_file(public_path($url))) {
            return public_path($url);
        }

        return null;
    }
}
