@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step {{ $step }} — Review & confirm</h2>

@if($student)
    <div class="mb-4 rounded-lg bg-gray-50 border p-4 text-sm">
        <p><strong>Student:</strong> {{ $student->name }} ({{ $student->email }})</p>
        <p><strong>Type:</strong> {{ str_replace('_', ' ', $wizard['booking_type']) }}</p>
        <p><strong>Weeks:</strong> {{ $wizard['weeks'] }} · <strong>Start:</strong> {{ $wizard['start_date'] }}</p>
    </div>
@endif

@if($pricing)
    <div class="mb-4 rounded-lg border p-4 text-sm space-y-2">
        <h3 class="font-semibold">Price breakdown</h3>
        @if(!empty($pricing['courseTotal']))
            <div class="flex justify-between"><span>Course</span><span>{{ number_format($pricing['courseTotal'], 2) }} {{ $wizard['display_currency'] }}</span></div>
        @endif
        @if(!empty($pricing['accPrice']))
            <div class="flex justify-between"><span>Accommodation</span><span>{{ number_format($pricing['accPrice'], 2) }} {{ $wizard['display_currency'] }}</span></div>
        @endif
        @if(!empty($pricing['pickupTotal']))
            <div class="flex justify-between"><span>Pickup</span><span>{{ number_format($pricing['pickupTotal'], 2) }} {{ $wizard['display_currency'] }}</span></div>
        @endif
        @if(!empty($pricing['courseDiscountAmount']))
            <div class="flex justify-between text-green-700"><span>Course discount</span><span>-{{ number_format($pricing['courseDiscountAmount'], 2) }} {{ $wizard['display_currency'] }}</span></div>
        @endif
        @if(!empty($pricing['pioneersCashTotal']))
            <div class="flex justify-between text-green-700"><span>Pioneers discount</span><span>-{{ number_format($pricing['pioneersCashTotal'], 2) }} {{ $wizard['display_currency'] }}</span></div>
        @endif
        <div class="flex justify-between font-bold text-base border-t pt-2">
            <span>Total</span>
            <span>{{ number_format($pricing['total'] ?? 0, 2) }} {{ $wizard['display_currency'] }}</span>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Notes (optional)</label>
        <textarea name="notes" rows="2" class="w-full rounded-lg border px-3 py-2">{{ old('notes', $wizard['notes'] ?? '') }}</textarea>
    </div>
    <div class="flex flex-wrap gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        @if(($wizard['mode'] ?? 'booking') === 'quotation')
            <button type="submit" name="action" value="quotation" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Create quotation</button>
        @else
            <button type="submit" name="action" value="booking" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Create booking</button>
            <button type="submit" name="action" value="quotation" class="rounded-lg border border-blue-600 text-blue-600 px-5 py-2.5 text-sm font-medium">Save as quotation too</button>
        @endif
    </div>
</form>
@endsection
