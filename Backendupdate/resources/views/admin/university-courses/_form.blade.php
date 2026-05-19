@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">University *</label>
                <select name="university_id" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Select university</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->id }}" {{ (string) old('university_id', $universityCourse->university_id ?? '') === (string) $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Course Catalog *</label>
                <select name="course_catalog_id" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Select catalog</option>
                    @foreach($catalogs as $catalog)
                        <option value="{{ $catalog->id }}" {{ (string) old('course_catalog_id', $universityCourse->course_catalog_id ?? '') === (string) $catalog->id ? 'selected' : '' }}>{{ $catalog->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Level *</label>
                <select name="level_id" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Select level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ (string) old('level_id', $universityCourse->level_id ?? '') === (string) $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Duration Value</label>
                <input type="number" min="1" name="duration_value" value="{{ old('duration_value', $universityCourse->duration_value ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Duration Unit</label>
                <select name="duration_unit" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Select unit</option>
                    @foreach(['month' => 'Month', 'year' => 'Year', 'week' => 'Week'] as $value => $label)
                        <option value="{{ $value }}" {{ old('duration_unit', $universityCourse->duration_unit ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">First Year Fee</label>
                <input type="number" step="0.01" name="first_year_fee" value="{{ old('first_year_fee', $universityCourse->first_year_fee ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $universityCourse->currency ?? 'USD') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Overview</label>
                <textarea name="overview" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('overview', $universityCourse->overview ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Overview</label>
                <textarea name="ar_overview" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_overview', $universityCourse->ar_overview ?? '') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Awarding Body</label>
                <input type="text" name="awarding_body" value="{{ old('awarding_body', $universityCourse->awarding_body ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Awarding Body</label>
                <input type="text" name="ar_awarding_body" value="{{ old('ar_awarding_body', $universityCourse->ar_awarding_body ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Degree Requirement</label>
                <textarea name="degree_requirement" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('degree_requirement', $universityCourse->degree_requirement ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Language Requirement</label>
                <textarea name="language_requirement" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('language_requirement', $universityCourse->language_requirement ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Intake Terms</h4>
        <div class="space-y-4">
            @foreach($intakes as $intake)
                @php($pivot = isset($selectedIntakes) ? $selectedIntakes->get($intake->id)?->pivot : null)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 border border-gray-100 dark:border-gray-700 rounded-lg p-4">
                    <label class="flex items-center gap-3 text-sm font-medium md:col-span-1">
                        <input type="checkbox" name="intakes[{{ $intake->id }}][selected]" value="1" {{ old("intakes.$intake->id.selected", $pivot ? 1 : 0) ? 'checked' : '' }}>
                        {{ $intake->name }}
                    </label>
                    <input type="hidden" name="intakes[{{ $intake->id }}][id]" value="{{ $intake->id }}">
                    <div>
                        <label class="block text-xs font-medium mb-1">Deadline</label>
                        <input type="date" name="intakes[{{ $intake->id }}][deadline_date]" value="{{ old("intakes.$intake->id.deadline_date", $pivot?->deadline_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Start Date</label>
                        <input type="date" name="intakes[{{ $intake->id }}][start_date]" value="{{ old("intakes.$intake->id.start_date", $pivot?->start_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    </div>
                </div>
            @endforeach
        </div>
        <label class="flex items-center gap-3 text-sm font-medium">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $universityCourse->is_active ?? true) ? 'checked' : '' }}>
            Active
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
        <a href="{{ route('admin.university-courses.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
    </div>
</div>
