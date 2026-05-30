@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step {{ $step }} — Select course</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div class="grid gap-2 max-h-96 overflow-y-auto">
        @forelse($courses as $course)
            @php
                $courseId = is_array($course) ? ($course['id'] ?? null) : ($course->id ?? null);
                $courseName = is_array($course) ? ($course['name'] ?? $course['title'] ?? 'Course') : ($course->name_en ?? 'Course');
            @endphp
            <label class="flex items-center gap-3 rounded-lg border px-4 py-3 cursor-pointer hover:border-blue-400 {{ (string)($wizard['course_id'] ?? '') === (string)$courseId ? 'border-blue-600 bg-blue-50' : '' }}">
                <input type="radio" name="course_id" value="{{ $courseId }}" @checked((string)($wizard['course_id'] ?? '') === (string)$courseId) required>
                <span class="font-medium">{{ $courseName }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500">No courses found.</p>
        @endforelse
    </div>
    <div class="flex gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        @if(collect($courses)->isNotEmpty())
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Continue to student →</button>
        @endif
    </div>
</form>
@endsection
