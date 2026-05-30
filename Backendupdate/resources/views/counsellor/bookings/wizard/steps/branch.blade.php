@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step 3 — Select branch / location</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div class="grid gap-2">
        @forelse($branches as $branch)
            <label class="flex items-center gap-3 rounded-lg border px-4 py-3 cursor-pointer hover:border-blue-400 {{ (string)($wizard['branch_id'] ?? '') === (string)$branch['id'] ? 'border-blue-600 bg-blue-50' : '' }}">
                <input type="radio" name="branch_id" value="{{ $branch['id'] }}" @checked((string)($wizard['branch_id'] ?? '') === (string)$branch['id']) required>
                <span>
                    <strong>{{ $branch['city_name'] ?? 'Branch' }}</strong>
                    @if(!empty($branch['country_name']))<span class="text-gray-500 text-sm"> · {{ $branch['country_name'] }}</span>@endif
                </span>
            </label>
        @empty
            <p class="text-sm text-gray-500">No branches available.</p>
        @endforelse
    </div>
    <div class="flex gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        @if($branches->isNotEmpty())
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Continue to courses →</button>
        @endif
    </div>
</form>
@endsection
