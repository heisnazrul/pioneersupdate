@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name', $universityCourseCatalog->name ?? '') }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Arabic Name</label>
            <input type="text" name="ar_name" value="{{ old('ar_name', $universityCourseCatalog->ar_name ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $universityCourseCatalog->slug ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Subject Area</label>
        <select name="subject_area_id" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            <option value="">Select subject area</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ (string) old('subject_area_id', $universityCourseCatalog->subject_area_id ?? '') === (string) $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-3">Available Levels</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($levels as $level)
                <label class="flex items-center gap-3 text-sm">
                    <input type="checkbox" name="levels[]" value="{{ $level->id }}" {{ in_array($level->id, old('levels', $selectedLevels ?? []), true) ? 'checked' : '' }}>
                    {{ $level->name }}
                </label>
            @endforeach
        </div>
    </div>

    <label class="flex items-center gap-3 text-sm font-medium">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $universityCourseCatalog->is_active ?? true) ? 'checked' : '' }}>
        Active
    </label>
</div>

<div class="flex gap-3">
    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
    <a href="{{ route('admin.university-course-catalogs.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
</div>
