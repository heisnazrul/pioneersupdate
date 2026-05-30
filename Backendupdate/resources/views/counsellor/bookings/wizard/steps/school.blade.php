@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step 2 — Select school</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div class="grid gap-2 max-h-96 overflow-y-auto">
        @forelse($schools as $school)
            <label class="flex items-center gap-3 rounded-lg border px-4 py-3 cursor-pointer hover:border-blue-400 {{ (string)($wizard['school_id'] ?? '') === (string)$school->id ? 'border-blue-600 bg-blue-50' : '' }}">
                <input type="radio" name="school_id" value="{{ $school->id }}" @checked((string)($wizard['school_id'] ?? '') === (string)$school->id) required>
                <span class="font-medium">{{ $school->name_en }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500">No schools found for this category.</p>
        @endforelse
    </div>
    <div class="flex gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        @if($schools->isNotEmpty())
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Continue →</button>
        @endif
    </div>
</form>
@endsection
