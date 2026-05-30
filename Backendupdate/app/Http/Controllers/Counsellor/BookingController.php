<?php

namespace App\Http\Controllers\Counsellor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\Concerns\InteractsWithStaffPanel;
use App\Models\BookingPaymentEvent;
use App\Services\Staff\BookingDetailService;
use App\Services\Staff\BookingPaymentService;
use App\Services\Staff\BookingPdfService;
use App\Services\Staff\BookingQueryService;
use App\Support\BookingStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    use InteractsWithStaffPanel;

    protected function panelRoutePrefix(): string
    {
        return 'counsellor';
    }

    protected function assignedScope(): ?int
    {
        return auth()->id();
    }

    protected function isTeamPanel(): bool
    {
        return false;
    }

    public function index(Request $request, BookingQueryService $service): View
    {
        return view('staff.bookings.index', [
            'bookings' => $service->paginate($request, $this->assignedScope()),
            'statuses' => BookingStatus::ALL,
            'activeStatus' => $request->query('status'),
            'activeType' => $request->query('type', 'all'),
            'search' => $request->query('search', ''),
            'routePrefix' => $this->panelRoutePrefix(),
            'showCounsellorFilter' => false,
            'counsellors' => collect(),
        ]);
    }

    public function show(string $type, int $id, BookingQueryService $query, BookingDetailService $detail): View
    {
        $booking = $query->resolveWithRelations($type, $id);
        $this->ensureBookingAccess($booking);

        $paymentService = app(BookingPaymentService::class);

        return view('staff.bookings.show', [
            'booking' => $booking,
            'bookingType' => $type,
            'detail' => $detail->payload($booking, $type),
            'statuses' => BookingStatus::ALL,
            'allowedTransitions' => $paymentService->allowedTransitions($booking->status, false),
            'events' => BookingPaymentEvent::query()
                ->where('booking_type', $type)
                ->where('booking_id', $booking->id)
                ->with('user')
                ->latest('id')
                ->get(),
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => false,
            'counsellors' => collect(),
        ]);
    }

    public function updateStatus(Request $request, string $type, int $id, BookingQueryService $query, BookingPaymentService $payment): RedirectResponse
    {
        $booking = $query->resolveModel($type, $id);
        $this->ensureBookingAccess($booking);

        $data = $request->validate([
            'status' => ['required', Rule::in(BookingStatus::ALL)],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'payment_notes' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'payment_proof' => ['nullable', 'file', 'max:5120'],
        ]);

        $payment->updateStatus(
            $booking,
            $type,
            $request->user(),
            $data['status'],
            $data,
            false,
        );

        return redirect()
            ->route($this->panelRoutePrefix() . '.bookings.show', ['type' => $type, 'id' => $id])
            ->with('success', 'Booking updated.');
    }

    public function pdfStudent(string $type, int $id, BookingQueryService $query, BookingPdfService $pdf): Response
    {
        $booking = $query->resolveWithRelations($type, $id);
        $this->ensureBookingAccess($booking);

        return $pdf->studentCopy($booking, $type);
    }

    public function pdfSchool(string $type, int $id, BookingQueryService $query, BookingPdfService $pdf): Response
    {
        $booking = $query->resolveWithRelations($type, $id);
        $this->ensureBookingAccess($booking);

        return $pdf->schoolCopy($booking, $type);
    }
}
