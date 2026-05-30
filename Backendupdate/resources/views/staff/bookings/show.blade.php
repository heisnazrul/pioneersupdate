@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'Booking ' . $booking->reference_no)
@section('header', 'Booking Details')

@section('content')
<div class="max-w-5xl">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="font-mono text-lg">{{ $booking->reference_no }}</p>
            <p class="text-sm text-gray-500">{{ str_replace('_', ' ', $bookingType) }} · {{ ucfirst($booking->status) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route($routePrefix . '.bookings.pdf.student', ['type' => $bookingType, 'id' => $booking->id]) }}" class="rounded border px-3 py-2 text-sm">Student PDF</a>
            <a href="{{ route($routePrefix . '.bookings.pdf.school', ['type' => $bookingType, 'id' => $booking->id]) }}" class="rounded border px-3 py-2 text-sm">School PDF</a>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 mb-6">
        <div class="rounded-xl border bg-white p-6">
            <h3 class="mb-3 text-xs font-semibold uppercase text-gray-500">Contact</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Name</dt><dd>{{ $booking->contact_name }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd>{{ $booking->contact_email }}</dd></div>
                <div><dt class="text-gray-500">Phone</dt><dd>{{ $booking->contact_phone ?: '—' }}</dd></div>
            </dl>
        </div>
        <div class="rounded-xl border bg-white p-6">
            <h3 class="mb-3 text-xs font-semibold uppercase text-gray-500">Booking</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">School</dt><dd>{{ $booking->school?->name_en ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Counsellor</dt><dd>{{ $booking->assignee?->name ?? 'Unassigned' }}</dd></div>
                <div><dt class="text-gray-500">Total</dt><dd class="font-semibold">{{ number_format((float) $booking->total_amount, 2) }} {{ $booking->display_currency }}</dd></div>
                @if($booking->paid_at)
                    <div><dt class="text-gray-500">Paid at</dt><dd>{{ $booking->paid_at->format('Y-m-d H:i') }}</dd></div>
                @endif
                @if($booking->payment_reference)
                    <div><dt class="text-gray-500">Payment ref</dt><dd>{{ $booking->payment_reference }}</dd></div>
                @endif
            </dl>
        </div>
    </div>

    @if($showAssign ?? false)
        <form method="POST" action="{{ route('team.bookings.assign', ['type' => $bookingType, 'id' => $booking->id]) }}" class="mb-6 rounded-xl border bg-white p-6">
            @csrf
            <h3 class="mb-3 font-semibold">Assign counsellor</h3>
            <div class="flex gap-2 items-end">
                <select name="assigned_to" class="rounded border px-3 py-2 text-sm">
                    <option value="">Unassigned</option>
                    @foreach($counsellors as $c)
                        <option value="{{ $c->id }}" @selected($booking->assigned_to == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Save</button>
            </div>
        </form>
    @endif

    <form method="POST" action="{{ route($routePrefix . '.bookings.status', ['type' => $bookingType, 'id' => $booking->id]) }}" enctype="multipart/form-data" class="rounded-xl border bg-white p-6 mb-6">
        @csrf
        <h3 class="mb-4 font-semibold">Payment & status</h3>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-xs text-gray-500 mb-1">New status</label>
                <select name="status" class="w-full rounded border px-3 py-2 text-sm" required>
                    <option value="{{ $booking->status }}">{{ ucfirst($booking->status) }} (current)</option>
                    @foreach($allowedTransitions as $transition)
                        @if($transition !== $booking->status)
                            <option value="{{ $transition }}">{{ ucfirst($transition) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Payment reference</label>
                <input type="text" name="payment_reference" value="{{ old('payment_reference', $booking->payment_reference) }}" class="w-full rounded border px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Payment method</label>
                <input type="text" name="payment_method" value="{{ old('payment_method', $booking->payment_method ?? 'bank_transfer') }}" class="w-full rounded border px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Payment proof</label>
                <input type="file" name="payment_proof" class="w-full text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Notes</label>
                <textarea name="payment_notes" rows="2" class="w-full rounded border px-3 py-2 text-sm">{{ old('payment_notes', $booking->payment_notes) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Audit note</label>
                <input type="text" name="note" class="w-full rounded border px-3 py-2 text-sm" placeholder="Reason for status change">
            </div>
            @if($routePrefix === 'team')
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="verify_payment" value="1" id="verify_payment">
                    <label for="verify_payment" class="text-sm">Verify payment when completing</label>
                </div>
            @endif
        </div>
        <button class="mt-4 rounded bg-blue-600 px-4 py-2 text-sm text-white">Update booking</button>
    </form>

    @if($events->isNotEmpty())
        <div class="rounded-xl border bg-white p-6 mb-6">
            <h3 class="mb-3 font-semibold">Status history</h3>
            <ul class="text-sm divide-y">
                @foreach($events as $event)
                    <li class="py-2">{{ $event->created_at?->format('Y-m-d H:i') }} — {{ $event->user?->name }}: {{ $event->old_status }} → {{ $event->new_status }} @if($event->note)<span class="text-gray-500">({{ $event->note }})</span>@endif</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route($routePrefix . '.bookings.index') }}" class="text-sm text-blue-600 hover:underline">← Back to bookings</a>
</div>
@endsection
