<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\User;
use App\Support\BookingStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseSatBookingController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'all');
        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 25;

        $language = collect();
        $online = collect();

        if ($type === 'all' || $type === 'language') {
            $language = LanguageCourseBooking::query()
                ->with(['user', 'school', 'course', 'bookedByAgent.user'])
                ->when($status, fn ($q) => $q->where('status', $status))
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('reference_no', 'like', "%{$search}%")
                            ->orWhere('contact_email', 'like', "%{$search}%")
                            ->orWhere('contact_name', 'like', "%{$search}%");
                    });
                })
                ->latest('id')
                ->limit(200)
                ->get()
                ->map(fn (LanguageCourseBooking $booking) => $this->mapRow($booking, 'language_course'));
        }

        if ($type === 'all' || $type === 'online') {
            $online = OnlineCourseBooking::query()
                ->with(['user', 'school', 'course', 'bookedByAgent.user'])
                ->when($status, fn ($q) => $q->where('status', $status))
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('reference_no', 'like', "%{$search}%")
                            ->orWhere('contact_email', 'like', "%{$search}%")
                            ->orWhere('contact_name', 'like', "%{$search}%");
                    });
                })
                ->latest('id')
                ->limit(200)
                ->get()
                ->map(fn (OnlineCourseBooking $booking) => $this->mapRow($booking, 'online_course'));
        }

        $merged = $language->concat($online)->sortByDesc('created_at')->values();
        $total = $merged->count();
        $items = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        $bookings = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('admin.course-sat-bookings.index', [
            'bookings' => $bookings,
            'statuses' => BookingStatus::ALL,
            'activeStatus' => $status,
            'activeType' => $type,
            'search' => $search,
        ]);
    }

    public function show(Request $request, string $type, int $id): View
    {
        $booking = $this->resolveBooking($type, $id);

        return view('admin.course-sat-bookings.show', [
            'booking' => $booking,
            'bookingType' => $type,
            'statuses' => BookingStatus::ALL,
        ]);
    }

    public function edit(Request $request, string $type, int $id): View
    {
        $booking = $this->resolveBooking($type, $id);

        return view('admin.course-sat-bookings.edit', [
            'booking' => $booking,
            'bookingType' => $type,
            'statuses' => BookingStatus::ALL,
            'staff' => User::query()->whereIn('role', ['admin', 'team', 'counsellor'])->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $booking = $this->resolveBookingModel($type, $id);

        $data = $request->validate([
            'status' => ['required', Rule::in(BookingStatus::ALL)],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $booking->update([
            'status' => $data['status'],
            'assigned_to' => $data['assigned_to'] ?? $booking->assigned_to,
            'notes' => $data['notes'] ?? $booking->notes,
        ]);

        return redirect()
            ->route('admin.course-sat-bookings.show', ['type' => $type, 'id' => $id])
            ->with('success', 'Booking updated successfully.');
    }

    private function resolveBooking(string $type, int $id): LanguageCourseBooking|OnlineCourseBooking
    {
        return $this->resolveBookingModel($type, $id)->load([
            'user',
            'school',
            'course',
            'bookedByAgent.user',
        ]);
    }

    private function resolveBookingModel(string $type, int $id): LanguageCourseBooking|OnlineCourseBooking
    {
        if ($type === 'online_course') {
            return OnlineCourseBooking::query()->findOrFail($id);
        }

        return LanguageCourseBooking::query()->findOrFail($id);
    }

    private function mapRow(LanguageCourseBooking|OnlineCourseBooking $booking, string $type): array
    {
        return [
            'id' => $booking->id,
            'type' => $type,
            'reference_no' => $booking->reference_no,
            'status' => $booking->status,
            'contact_name' => $booking->contact_name,
            'contact_email' => $booking->contact_email,
            'total_amount' => (float) $booking->total_amount,
            'display_currency' => $booking->display_currency,
            'school_name' => $booking->school?->name_en ?? $booking->school?->name,
            'created_at' => $booking->created_at,
            'booked_by_agent' => $booking->bookedByAgent?->user?->name,
        ];
    }
}
