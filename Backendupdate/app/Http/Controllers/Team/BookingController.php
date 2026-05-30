<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Counsellor\BookingController as CounsellorBookingController;
use App\Models\BookingPaymentEvent;
use App\Services\Staff\BookingDetailService;
use App\Services\Staff\BookingPaymentService;
use App\Services\Staff\BookingPdfService;
use App\Services\Staff\BookingQueryService;
use App\Services\Staff\StaffAssignmentService;
use App\Support\BookingStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends CounsellorBookingController
{
    protected function panelRoutePrefix(): string
    {
        return 'team';
    }

    protected function assignedScope(): ?int
    {
        return null;
    }

    protected function isTeamPanel(): bool
    {
        return true;
    }

    public function index(Request $request, BookingQueryService $service): View
    {
        return view('staff.bookings.index', [
            'bookings' => $service->paginate($request, null),
            'statuses' => BookingStatus::ALL,
            'activeStatus' => $request->query('status'),
            'activeType' => $request->query('type', 'all'),
            'search' => $request->query('search', ''),
            'routePrefix' => $this->panelRoutePrefix(),
            'showCounsellorFilter' => true,
            'counsellors' => $this->counsellors(),
            'activeCounsellor' => $request->query('counsellor_id'),
        ]);
    }

    public function show(string $type, int $id, BookingQueryService $query, BookingDetailService $detail): View
    {
        $booking = $query->resolveWithRelations($type, $id);
        $paymentService = app(BookingPaymentService::class);

        return view('staff.bookings.show', [
            'booking' => $booking,
            'bookingType' => $type,
            'detail' => $detail->payload($booking, $type),
            'statuses' => BookingStatus::ALL,
            'allowedTransitions' => $paymentService->allowedTransitions($booking->status, true),
            'events' => BookingPaymentEvent::query()
                ->where('booking_type', $type)
                ->where('booking_id', $booking->id)
                ->with('user')
                ->latest('id')
                ->get(),
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => true,
            'counsellors' => $this->counsellors(),
        ]);
    }

    public function assign(Request $request, string $type, int $id, BookingQueryService $query, StaffAssignmentService $assignments): RedirectResponse
    {
        $booking = $query->resolveModel($type, $id);
        $data = $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $assignments->assignBooking($booking, $data['assigned_to'] ?? null);

        return back()->with('success', 'Counsellor assignment updated.');
    }

    public function updateStatus(Request $request, string $type, int $id, BookingQueryService $query, BookingPaymentService $payment): RedirectResponse
    {
        $booking = $query->resolveModel($type, $id);

        $data = $request->validate([
            'status' => ['required', Rule::in(BookingStatus::ALL)],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'payment_notes' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'payment_proof' => ['nullable', 'file', 'max:5120'],
            'verify_payment' => ['nullable', 'boolean'],
        ]);

        $payment->updateStatus(
            $booking,
            $type,
            $request->user(),
            $data['status'],
            $data,
            true,
        );

        return redirect()
            ->route('team.bookings.show', ['type' => $type, 'id' => $id])
            ->with('success', 'Booking updated.');
    }

    public function pdfStudent(string $type, int $id, BookingQueryService $query, BookingPdfService $pdf): Response
    {
        $booking = $query->resolveWithRelations($type, $id);

        return $pdf->studentCopy($booking, $type);
    }

    public function pdfSchool(string $type, int $id, BookingQueryService $query, BookingPdfService $pdf): Response
    {
        $booking = $query->resolveWithRelations($type, $id);

        return $pdf->schoolCopy($booking, $type);
    }
}
