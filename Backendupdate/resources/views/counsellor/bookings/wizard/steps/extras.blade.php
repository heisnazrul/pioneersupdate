@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step {{ $step }} — Accommodation & extras</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Accommodation</label>
        <select name="accommodation_id" class="w-full rounded-lg border px-3 py-2">
            <option value="no-acc" @selected(($wizard['accommodation_id'] ?? 'no-acc') === 'no-acc')>No accommodation</option>
            @foreach($catalog['accommodations'] ?? [] as $acc)
                <option value="{{ $acc['id'] }}" @selected((string)($wizard['accommodation_id'] ?? '') === (string)$acc['id'])>{{ $acc['name'] ?? 'Accommodation' }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Airport pickup</label>
        <select name="pickup_id" class="w-full rounded-lg border px-3 py-2">
            <option value="">No pickup</option>
            @foreach($catalog['pickups'] ?? [] as $pickup)
                <option value="{{ $pickup['id'] }}" @selected((string)($wizard['pickup_id'] ?? '') === (string)$pickup['id'])>{{ $pickup['name'] ?? 'Pickup' }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-2">Insurance</label>
        @forelse($catalog['insurances'] ?? [] as $ins)
            <label class="flex items-center gap-2 text-sm py-1">
                <input type="checkbox" name="insurance_ids[]" value="{{ $ins['id'] }}"
                    @checked(in_array((int)$ins['id'], array_map('intval', $wizard['insurance_ids'] ?? []), true))>
                {{ $ins['name'] ?? 'Insurance' }}
            </label>
        @empty
            <p class="text-sm text-gray-500">No optional insurance for this branch.</p>
        @endforelse
    </div>
    @if(!empty($catalog['pioneers_discounts']))
        <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 text-sm text-amber-900">
            Pioneers discounts and course promotions are applied automatically on the review step.
        </div>
    @endif
    <div class="flex gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Continue to review →</button>
    </div>
</form>
@endsection
