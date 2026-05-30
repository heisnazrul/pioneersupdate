<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;

class BookingQueryService
{
    public function paginate(Request $request, ?int $assignedTo = null): LengthAwarePaginator
    {
        $type = $request->query('type', 'all');
        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));
        $counsellorId = $request->query('counsellor_id');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 25;

        $language = collect();
        $online = collect();

        if ($type === 'all' || $type === 'language') {
            $language = $this->languageQuery($assignedTo, $counsellorId, $status, $search)
                ->latest('id')
                ->limit(500)
                ->get()
                ->map(fn (LanguageCourseBooking $booking) => $this->mapRow($booking, 'language_course'));
        }

        if ($type === 'all' || $type === 'online') {
            $online = $this->onlineQuery($assignedTo, $counsellorId, $status, $search)
                ->latest('id')
                ->limit(500)
                ->get()
                ->map(fn (OnlineCourseBooking $booking) => $this->mapRow($booking, 'online_course'));
        }

        $merged = $language->concat($online)->sortByDesc('created_at')->values();
        $total = $merged->count();
        $items = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        return new Paginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );
    }

    public function countsByStatus(?int $assignedTo = null): array
    {
        $counts = array_fill_keys(\App\Support\BookingStatus::ALL, 0);

        foreach ($this->languageQuery($assignedTo)->get(['status']) as $booking) {
            $counts[$booking->status] = ($counts[$booking->status] ?? 0) + 1;
        }

        foreach ($this->onlineQuery($assignedTo)->get(['status']) as $booking) {
            $counts[$booking->status] = ($counts[$booking->status] ?? 0) + 1;
        }

        return $counts;
    }

    public function pendingPaymentCount(?int $assignedTo = null): int
    {
        return $this->languageQuery($assignedTo)->where('status', 'confirmed')->count()
            + $this->onlineQuery($assignedTo)->where('status', 'confirmed')->count();
    }

    public function resolveModel(string $type, int $id): LanguageCourseBooking|OnlineCourseBooking
    {
        if ($type === 'online_course') {
            return OnlineCourseBooking::query()->findOrFail($id);
        }

        return LanguageCourseBooking::query()->findOrFail($id);
    }

    public function resolveWithRelations(string $type, int $id): LanguageCourseBooking|OnlineCourseBooking
    {
        return $this->resolveModel($type, $id)->load([
            'user',
            'school',
            'course',
            'bookedByAgent.user',
            'assignee',
        ]);
    }

    public function userCanAccess(LanguageCourseBooking|OnlineCourseBooking $booking, ?int $assignedTo): bool
    {
        if ($assignedTo === null) {
            return true;
        }

        return (int) $booking->assigned_to === $assignedTo;
    }

    private function languageQuery(?int $assignedTo, ?int $counsellorId = null, ?string $status = null, string $search = '')
    {
        return LanguageCourseBooking::query()
            ->with(['user', 'school', 'course', 'bookedByAgent.user', 'assignee'])
            ->when($assignedTo, fn ($q) => $q->where('assigned_to', $assignedTo))
            ->when($counsellorId, fn ($q) => $q->where('assigned_to', $counsellorId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('contact_email', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%");
                });
            });
    }

    private function onlineQuery(?int $assignedTo, ?int $counsellorId = null, ?string $status = null, string $search = '')
    {
        return OnlineCourseBooking::query()
            ->with(['user', 'school', 'course', 'bookedByAgent.user', 'assignee'])
            ->when($assignedTo, fn ($q) => $q->where('assigned_to', $assignedTo))
            ->when($counsellorId, fn ($q) => $q->where('assigned_to', $counsellorId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('contact_email', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%");
                });
            });
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
            'assigned_to' => $booking->assigned_to,
            'assignee_name' => $booking->assignee?->name,
            'created_at' => $booking->created_at,
            'booked_by_agent' => $booking->bookedByAgent?->user?->name,
        ];
    }
}
