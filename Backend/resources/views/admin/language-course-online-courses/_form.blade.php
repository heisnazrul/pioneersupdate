@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" value="{{ old('name', $course->name ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Name</label>
        <input type="text" name="ar_name" value="{{ old('ar_name', $course->ar_name ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">School</label>
        <select name="language_school_id" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="">Select school</option>
            @foreach($schools as $s)
                <option value="{{ $s->id }}" {{ (int)old('language_school_id', $course->language_school_id ?? 0) === $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Course Type</label>
        <select name="course_type_id" class="mt-1 w-full border rounded px-3 py-2" required>
            @foreach($types as $t)
                <option value="{{ $t->id }}" {{ (int)old('course_type_id', $course->course_type_id ?? 0) === $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tag</label>
        <select name="tag_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">None</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ (int)old('tag_id', $course->tag_id ?? 0) === $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Fee Type</label>
        <select name="fee_type" class="mt-1 w-full border rounded px-3 py-2" required>
            @php $feeType = old('fee_type', $course->fee_type ?? 'weekly'); @endphp
            <option value="weekly" {{ $feeType==='weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="flat" {{ $feeType==='flat' ? 'selected' : '' }}>Flat</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Fee Amount</label>
        <input type="number" step="0.01" min="0" name="fee_amount" value="{{ old('fee_amount', $course->fee_amount ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Registration Fee</label>
        <input type="number" step="0.01" min="0" name="registration_fee" value="{{ old('registration_fee', $course->registration_fee ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Lessons / week</label>
        <input type="number" min="0" name="lessons_per_week" value="{{ old('lessons_per_week', $course->lessons_per_week ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Min Age</label>
        <input type="number" min="0" name="min_age" value="{{ old('min_age', $course->min_age ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Required Level</label>
        <input type="text" name="required_level" value="{{ old('required_level', $course->required_level ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Study Time</label>
        <input type="text" name="study_time" value="{{ old('study_time', $course->study_time ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="text" name="start_date" value="{{ old('start_date', $course->start_date ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        @php $status = old('status', $course->status ?? 'published'); @endphp
        <select name="status" class="mt-1 w-full border rounded px-3 py-2">
            <option value="draft" {{ $status==='draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ $status==='published' ? 'selected' : '' }}>Published</option>
            <option value="suspended" {{ $status==='suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
    </div>
</div>

<div class="mt-3">
    <label class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $course->description ?? '') }}</textarea>
</div>
<div class="mt-3">
    <label class="block text-sm font-medium text-gray-700">Arabic Description</label>
    <textarea name="ar_description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_description', $course->ar_description ?? '') }}</textarea>
</div>

<div class="mt-3">
    <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
    <input type="file" name="thumbnail" accept="image/*" class="mt-1 w-full border rounded px-3 py-2">
    @if(!empty($course?->thumbnail))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail" class="h-16 w-24 object-cover rounded border">
        </div>
    @endif
</div>

<div class="flex items-center gap-3 mt-4">
    <input type="hidden" name="visible" value="0">
    <input type="checkbox" name="visible" value="1" {{ old('visible', $course->visible ?? 1) ? 'checked' : '' }}>
    <span class="text-sm text-gray-700">Visible</span>
</div>
