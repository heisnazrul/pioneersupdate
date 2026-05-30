@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'New Quotation')
@section('header', 'Create Quotation')

@section('content')
<form method="POST" action="{{ route($routePrefix . '.quotations.store') }}" class="max-w-2xl rounded-xl border bg-white p-6 space-y-4">
    @csrf
    <div>
        <label class="block text-xs text-gray-500 mb-1">Student</label>
        <select name="student_user_id" required class="w-full rounded border px-3 py-2 text-sm">
            <option value="">Select student</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_user_id') == $student->id)>{{ $student->name }} ({{ $student->email }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Booking type</label>
        <select name="booking_type" class="w-full rounded border px-3 py-2 text-sm">
            <option value="language_course">Language course</option>
            <option value="online_course">Online course</option>
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">School</label>
        <select name="language_school_id" class="w-full rounded border px-3 py-2 text-sm">
            <option value="">Optional</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name_en }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Course ID</label>
            <input type="number" name="course_id" value="{{ old('course_id') }}" class="w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Weeks</label>
            <input type="number" name="weeks" value="{{ old('weeks', 1) }}" min="1" class="w-full rounded border px-3 py-2 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Start date</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Valid until</label>
            <input type="date" name="valid_until" value="{{ old('valid_until') }}" class="w-full rounded border px-3 py-2 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Total amount</label>
            <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount') }}" required class="w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Currency</label>
            <input type="text" name="display_currency" value="{{ old('display_currency', 'SAR') }}" class="w-full rounded border px-3 py-2 text-sm">
        </div>
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Notes</label>
        <textarea name="notes" rows="3" class="w-full rounded border px-3 py-2 text-sm">{{ old('notes') }}</textarea>
    </div>
    <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Save draft</button>
</form>
@endsection
