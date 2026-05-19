<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-6">
        <label for="university_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">University
            <span class="text-red-500">*</span></label>
        <select name="university_id" id="university_id"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            required>
            <option value="">Select University</option>
            @foreach($universities as $uni)
                <option value="{{ $uni->id }}" {{ old('university_id', $universityCourse->university_id ?? '') == $uni->id ? 'selected' : '' }}>
                    {{ $uni->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="course_catalog_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Course Catalog
            <span class="text-red-500">*</span></label>
        <select name="course_catalog_id" id="course_catalog_id"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            required>
            <option value="">Select Course</option>
            @foreach($catalogs as $catalog)
                <option value="{{ $catalog->id }}" {{ old('course_catalog_id', $universityCourse->course_catalog_id ?? '') == $catalog->id ? 'selected' : '' }}>
                    {{ $catalog->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="level_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Study Level <span
                class="text-red-500">*</span></label>
        <select name="level_id" id="level_id"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            required>
            <option value="">Select Level</option>
            @foreach($levels as $level)
                <option value="{{ $level->id }}" {{ old('level_id', $universityCourse->level_id ?? '') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Duration (Optional)</label>
        <div class="flex gap-2">
            <input type="number" name="duration_value"
                class="form-control w-1/2 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                value="{{ old('duration_value', $universityCourse->duration_value ?? '') }}"
                placeholder="Value (e.g. 3)">
            <select name="duration_unit"
                class="form-control w-1/2 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="">Select unit</option>
            <option value="year" {{ old('duration_unit', $universityCourse->duration_unit ?? '') == 'year' ? 'selected' : '' }}>Years</option>
            <option value="month" {{ old('duration_unit', $universityCourse->duration_unit ?? '') == 'month' ? 'selected' : '' }}>Months</option>
            <option value="week" {{ old('duration_unit', $universityCourse->duration_unit ?? '') == 'week' ? 'selected' : '' }}>Weeks</option>
        </select>
        </div>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="first_year_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">First Year Fee</label>
        <div class="flex gap-2">
            <input type="number" step="0.01" name="first_year_fee"
                class="form-control w-2/3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                value="{{ old('first_year_fee', $universityCourse->first_year_fee ?? '') }}"
                placeholder="e.g. 15000">
            <input type="text" name="currency"
                class="form-control w-1/3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                value="{{ old('currency', $universityCourse->currency ?? 'USD') }}"
                maxlength="3" placeholder="USD">
        </div>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="awarding_body" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Awarding Body
            (Optional)</label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="awarding_body" name="awarding_body"
            value="{{ old('awarding_body', $universityCourse->awarding_body ?? '') }}"
            placeholder="e.g. University of Example">
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="ar_awarding_body" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Awarding Body (Arabic)</label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="ar_awarding_body" name="ar_awarding_body"
            value="{{ old('ar_awarding_body', $universityCourse->ar_awarding_body ?? '') }}">
    </div>

    <div class="col-span-12">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Intake Terms</label>
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-4 border border-gray-200 dark:border-white/10 rounded-md p-4 bg-gray-50/50">
            @php
                $existingPivot = isset($universityCourse) ? $universityCourse->intakeTerms->keyBy('id') : collect([]);
            @endphp
            @foreach($intakes as $intake)
                @php
                    $isSelected = $existingPivot->has($intake->id) || old("intakes.{$intake->id}.selected");
                    $pivotData = $existingPivot->get($intake->id) ? $existingPivot->get($intake->id)->pivot : null;
                    $deadline = old("intakes.{$intake->id}.deadline_date", $pivotData->deadline_date ?? '');
                    $startDate = old("intakes.{$intake->id}.start_date", $pivotData->start_date ?? '');

                    // Format dates for input type="date" (Y-m-d)
                    if ($deadline && $deadline instanceof \DateTime)
                        $deadline = $deadline->format('Y-m-d');
                    if ($startDate && $startDate instanceof \DateTime)
                        $startDate = $startDate->format('Y-m-d');
                @endphp

                <div class="col-span-1 border-b border-gray-200 dark:border-white/10 pb-4 last:border-0">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="intakes[{{ $intake->id }}][selected]" value="1"
                                class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary toggle-intake-fields"
                                data-target="intake-fields-{{ $intake->id }}" {{ $isSelected ? 'checked' : '' }}>
                        </div>
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $intake->name }}</label>

                            <div id="intake-fields-{{ $intake->id }}"
                                class="{{ $isSelected ? '' : 'hidden' }} mt-2 grid grid-cols-2 gap-2 transition-all">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Start Date</label>
                                    <input type="date" name="intakes[{{ $intake->id }}][start_date]"
                                        value="{{ $startDate }}"
                                        class="form-control w-full text-xs py-1 px-2 rounded border-gray-300">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Deadline</label>
                                    <input type="date" name="intakes[{{ $intake->id }}][deadline_date]"
                                        value="{{ $deadline }}"
                                        class="form-control w-full text-xs py-1 px-2 rounded border-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-intake-fields').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const targetId = this.getAttribute('data-target');
                const targetDiv = document.getElementById(targetId);
                if (this.checked) {
                    targetDiv.classList.remove('hidden');
                } else {
                    targetDiv.classList.add('hidden');
                }
            });
        });
    </script>

    <div class="col-span-12">
        <label for="overview" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Overview</label>
        <textarea name="overview" id="overview" rows="4"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('overview', $universityCourse->overview ?? '') }}</textarea>
    </div>

    <div class="col-span-12">
        <label for="degree_requirement" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Degree Requirement</label>
        <textarea name="degree_requirement" id="degree_requirement" rows="3"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('degree_requirement', $universityCourse->degree_requirement ?? '') }}</textarea>
    </div>

    <div class="col-span-12">
        <label for="language_requirement" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Language Requirement</label>
        <textarea name="language_requirement" id="language_requirement" rows="3"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('language_requirement', $universityCourse->language_requirement ?? '') }}</textarea>
    </div>

    <div class="col-span-12">
        <label for="ar_overview" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Overview (Arabic)</label>
        <textarea name="ar_overview" id="ar_overview" rows="4"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('ar_overview', $universityCourse->ar_overview ?? '') }}</textarea>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status</label>
        <select name="is_active" id="is_active"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="1" {{ old('is_active', $universityCourse->is_active ?? 1) == 1 ? 'selected' : '' }}>Active
            </option>
            <option value="0" {{ old('is_active', $universityCourse->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive
            </option>
        </select>
    </div>
</div>
