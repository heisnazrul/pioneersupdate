@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step 1 — Course type & category</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <input type="hidden" name="mode" value="{{ $wizard['mode'] ?? 'booking' }}">
    <div>
        <label class="block text-sm font-medium mb-2">What are you creating?</label>
        <div class="grid gap-3 sm:grid-cols-2">
            <label class="flex items-start gap-3 rounded-lg border p-4 cursor-pointer {{ ($wizard['booking_type'] ?? '') === 'language_course' ? 'border-blue-600 bg-blue-50' : '' }}">
                <input type="radio" name="booking_type" value="language_course" class="mt-1" @checked(($wizard['booking_type'] ?? 'language_course') === 'language_course')>
                <span><strong>Language course</strong><br><span class="text-xs text-gray-500">In-person at a school branch</span></span>
            </label>
            <label class="flex items-start gap-3 rounded-lg border p-4 cursor-pointer {{ ($wizard['booking_type'] ?? '') === 'online_course' ? 'border-blue-600 bg-blue-50' : '' }}">
                <input type="radio" name="booking_type" value="online_course" class="mt-1" @checked(($wizard['booking_type'] ?? '') === 'online_course')>
                <span><strong>Online course</strong><br><span class="text-xs text-gray-500">Remote / live online program</span></span>
            </label>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Course category</label>
        <select name="category_id" class="w-full rounded-lg border px-3 py-2">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected((string)($wizard['category_id'] ?? '') === (string)$cat->id)>{{ $cat->name_en }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Continue to school selection →</button>
</form>
@endsection
