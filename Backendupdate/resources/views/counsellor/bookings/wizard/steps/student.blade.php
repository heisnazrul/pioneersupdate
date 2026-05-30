@extends('counsellor.bookings.wizard._layout')

@section('wizard_step')
<h2 class="text-lg font-semibold mb-4">Step {{ $step }} — Select student</h2>
<form method="POST" action="{{ route('counsellor.bookings.wizard.process', ['step' => $step]) }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Student</label>
        <select name="student_user_id" required class="w-full rounded-lg border px-3 py-2">
            <option value="">Choose a student</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected((string)($wizard['student_user_id'] ?? '') === (string)$student->id)>
                    {{ $student->name }} ({{ $student->email }})
                </option>
            @endforeach
        </select>
        <p class="mt-2 text-xs text-gray-500">
            Don't see the student?
            <a href="{{ route('counsellor.students.create') }}" class="text-blue-600 underline">Create a new student</a>
            then return here.
        </p>
    </div>
    <div class="flex gap-2 pt-2">
        <a href="{{ route('counsellor.bookings.wizard.back', ['step' => $step]) }}" class="rounded-lg border px-4 py-2 text-sm">← Back</a>
        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white">Continue to schedule →</button>
    </div>
</form>
@endsection
