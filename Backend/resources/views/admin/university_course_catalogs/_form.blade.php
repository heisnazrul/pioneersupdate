@php
    $catalog = $universityCourseCatalog ?? null;
    $selectedLevels = $selectedLevels ?? ($catalog ? $catalog->levels()->pluck('levels.id')->all() : []);
@endphp

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Course Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $catalog->name ?? '') }}"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            required>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Course Name (Arabic)</label>
        <input type="text" name="ar_name" value="{{ old('ar_name', $catalog->ar_name ?? '') }}"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
    </div>

    <div class="col-span-12 md:col-span-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Slug (Auto)</label>
        <input type="text" name="slug" value="{{ old('slug', $catalog->slug ?? '') }}"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            placeholder="Leave empty to auto-generate">
    </div>

    <div class="col-span-12 md:col-span-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Subject Area</label>
        <select name="subject_area_id"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ (int)old('subject_area_id', $catalog->subject_area_id ?? 0) === $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Available Levels</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 border border-gray-200 dark:border-white/10 rounded-md p-4 bg-gray-50/50">
            @foreach($levels as $level)
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                    <input type="checkbox" name="levels[]" value="{{ $level->id }}"
                        class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded"
                        {{ in_array($level->id, old('levels', $selectedLevels)) ? 'checked' : '' }}>
                    <span>{{ $level->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status</label>
        <select name="is_active"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="1" {{ old('is_active', $catalog->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $catalog->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>
