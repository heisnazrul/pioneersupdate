<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class BookingPdfService
{
    public function __construct(
        private readonly BookingDetailService $detailService,
    ) {
    }

    private function configurePdf(\Barryvdh\DomPDF\PDF $pdf): \Barryvdh\DomPDF\PDF
    {
        return $pdf
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultMediaType', 'print');
    }

    public function schoolCopy(LanguageCourseBooking|OnlineCourseBooking $booking, string $type): Response
    {
        $detail = $this->detailService->payload($booking, $type, true) ?? [];
        $filename = ($booking->reference_no ?? 'booking') . '-school.pdf';

        return $this->downloadPdf('pdf.bookings.school-copy', $filename, $booking, $detail, 'school');
    }

    public function studentCopy(LanguageCourseBooking|OnlineCourseBooking $booking, string $type): Response
    {
        $detail = $this->detailService->payload($booking, $type) ?? [];
        $filename = ($booking->reference_no ?? 'booking') . '-student.pdf';

        return $this->downloadPdf('pdf.bookings.student-copy', $filename, $booking, $detail, 'student');
    }

    public function quotationProforma(Quotation $quotation): Response
    {
        $quotation->load(['student', 'school']);
        $filename = ($quotation->reference_no ?? 'quote') . '.pdf';
        $invoice = app(BookingInvoiceViewData::class)->fromQuotation($quotation);

        return $this->configurePdf(Pdf::loadView('pdf.quotations.proforma', [
            'quotation' => $quotation,
            'invoice' => $invoice,
        ]))->download($filename);
    }

    /** @param  array<string, mixed>  $detail */
    private function downloadPdf(
        string $view,
        string $filename,
        LanguageCourseBooking|OnlineCourseBooking $booking,
        array $detail,
        string $copy,
    ): Response {
        return $this->configurePdf(Pdf::loadView($view, [
            'booking' => $booking,
            'detail' => $detail,
            'type' => $booking instanceof OnlineCourseBooking ? 'online_course' : 'language_course',
            'invoice' => app(BookingInvoiceViewData::class)->fromDetail($detail, $booking, $copy),
        ]))->download($filename);
    }
}
