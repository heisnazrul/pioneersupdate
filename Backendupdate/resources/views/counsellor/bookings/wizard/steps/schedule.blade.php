@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step {{ $step }} — Schedule & currency</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Number of weeks</label>
            <input type="number" name="weeks" min="1" value="{{ old('weeks', $wizard['weeks'] ?? 4) }}" required class="w-full rounded-lg border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Start date</label>
            <input type="date" name="start_date" value="{{ old('start_date', $wizard['start_date'] ?? '') }}" required class="w-full rounded-lg border px-3 py-2">
        </div>
        @if(($wizard['booking_type'] ?? '') === 'language_course')
            <div>
                <label class="block text-sm font-medium mb-1">Student age</label>
                <input type="number" name="acc_age" min="1" value="{{ old('acc_age', $wizard['acc_age'] ?? 18) }}" class="w-full rounded-lg border px-3 py-2">
            </div>
        @endif
        <div>
            <label class="block text-sm font-medium mb-1">Display currency</label>
            <select name="display_currency" class="w-full rounded-lg border px-3 py-2">
                @foreach(['SAR', 'GBP', 'USD', 'EUR'] as $code)
                    <option value="{{ $code }}" @selected(($wizard['display_currency'] ?? 'SAR') === $code)>{{ $code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">
            @if(($wizard['booking_type'] ?? '') === 'language_course')
                Continue to extras →
            @else
                Continue to review →
            @endif
        </button>
    </div>
</form>
@endsection
