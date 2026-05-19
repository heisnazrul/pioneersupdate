<div class="grid grid-cols-12 gap-6">
    <!-- Left Column: Main Content -->
    <div class="col-span-12 xl:col-span-8 space-y-6">

        <!-- Basic Information Card -->
        <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
            <div
                class="box-header !border-b !border-gray-100 dark:!border-white/10 !py-4 !px-5 bg-gray-50/50 dark:bg-white/5">
                <div class="box-title text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="ri-article-line text-primary"></i> Basic Details
                </div>
            </div>
            <div class="box-body !p-6 space-y-6">
                <div class="grid grid-cols-12 gap-6">
                    <!-- Destination Name -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Destination Name
                            <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-map-pin-user-line text-lg"></i></span>
                            <input type="text"
                                class="form-control pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="name" name="name" value="{{ old('name', $destination->name ?? '') }}"
                                placeholder="e.g. United Kingdom" required>
                        </div>
                    </div>

                    <!-- Destination Name (Arabic) -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="ar_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Destination Name (Arabic)</label>
                        <input type="text"
                            class="form-control pl-3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                            id="ar_name" name="ar_name" value="{{ old('ar_name', $destination->ar_name ?? '') }}"
                            placeholder="اسم الوجهة">
                    </div>

                    <!-- Slug -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Slug
                            (Optional)</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-link text-lg"></i></span>
                            <input type="text"
                                class="form-control pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="slug" name="slug" value="{{ old('slug', $destination->slug ?? '') }}"
                                placeholder="Leave empty to auto-generate">
                        </div>
                    </div>

                    <!-- Region -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="region"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Region</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-global-line text-lg"></i></span>
                            <input type="text"
                                class="form-control pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="region" name="region" value="{{ old('region', $destination->region ?? '') }}"
                                placeholder="e.g. Western Europe">
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label for="ar_region"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Region (Arabic)</label>
                        <input type="text"
                            class="form-control pl-3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                            id="ar_region" name="ar_region" value="{{ old('ar_region', $destination->ar_region ?? '') }}">
                    </div>

                    <!-- Linked Country -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="country_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Linked
                            Country</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-flag-line text-lg"></i></span>
                            <select name="country_id" id="country_id" style="padding-left: 2.3rem"
                                class="form-control bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
                                <option value="">Select a Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ (old('country_id', $destination->country_id ?? '') == $country->id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1"><i
                                class="ri-information-line"></i> Links to university search.</p>
                    </div>

                    <!-- Short Pitch -->
                    <div class="col-span-12">
                        <label for="short_pitch"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Short Pitch</label>
                        <div class="relative w-full">
                            <textarea
                                class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm"
                                id="short_pitch" name="short_pitch" rows="2"
                                placeholder="A catchy one-liner about studying here...">{{ old('short_pitch', $destination->short_pitch ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <label for="ar_short_pitch"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Short Pitch (Arabic)</label>
                        <div class="relative w-full">
                            <textarea
                                class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm"
                                id="ar_short_pitch" name="ar_short_pitch" rows="2">{{ old('ar_short_pitch', $destination->ar_short_pitch ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <div class="col-span-12">
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Full
                            Description</label>
                        <div class="relative w-full">
                            <textarea
                                class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm"
                                id="description" name="description" rows="5"
                                placeholder="Detailed overview of the destination...">{{ old('description', $destination->description ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <label for="ar_description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Full Description (Arabic)</label>
                        <div class="relative w-full">
                            <textarea
                                class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm"
                                id="ar_description" name="ar_description" rows="5">{{ old('ar_description', $destination->ar_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arabic Quick Stats -->
        <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
            <div class="box-header !border-b !border-gray-100 dark:!border-white/10 !py-4 !px-5 bg-gray-50/50 dark:bg-white/5">
                <div class="box-title text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="ri-translate-2 text-primary"></i> Arabic Stats
                </div>
            </div>
            <div class="box-body !p-6">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Tuition Range (Arabic)</label>
                        <input type="text" name="ar_tuition_range" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 rounded-md text-sm py-2.5"
                            value="{{ old('ar_tuition_range', $destination->ar_tuition_range ?? '') }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Visa Timeline (Arabic)</label>
                        <input type="text" name="ar_visa_timeline" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 rounded-md text-sm py-2.5"
                            value="{{ old('ar_visa_timeline', $destination->ar_visa_timeline ?? '') }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Work Rights (Arabic)</label>
                        <input type="text" name="ar_work_rights" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 rounded-md text-sm py-2.5"
                            value="{{ old('ar_work_rights', $destination->ar_work_rights ?? '') }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Scholarships Summary (Arabic)</label>
                        <input type="text" name="ar_scholarships_summary" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 rounded-md text-sm py-2.5"
                            value="{{ old('ar_scholarships_summary', $destination->ar_scholarships_summary ?? '') }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Entry Requirement - GPA (Arabic)</label>
                        <textarea name="ar_entry_req_gpa" rows="2"
                            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 rounded-md text-sm">{{ old('ar_entry_req_gpa', $destination->ar_entry_req_gpa ?? '') }}</textarea>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Entry Requirement - Language (Arabic)</label>
                        <textarea name="ar_entry_req_language" rows="2"
                            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 rounded-md text-sm">{{ old('ar_entry_req_language', $destination->ar_entry_req_language ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Card (Restored to Main Column) -->
        <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
            <div
                class="box-header !border-b !border-gray-100 dark:!border-white/10 !py-4 !px-5 bg-gray-50/50 dark:bg-white/5">
                <div class="box-title text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="ri-bar-chart-groupped-line text-info"></i> Quick Stats
                </div>
            </div>
            <div class="box-body !p-6">
                <div class="grid grid-cols-12 gap-6">
                    <!-- Tuition Range -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="tuition_range"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Tuition
                            Range</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-money-dollar-circle-line text-lg"></i></span>
                            <input type="text"
                                class="form-control w-full pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="tuition_range" name="tuition_range"
                                value="{{ old('tuition_range', $destination->tuition_range ?? '') }}"
                                placeholder="$10k - $20k">
                        </div>
                    </div>

                    <!-- Visa Timeline -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="visa_timeline"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Visa Processing
                            Time</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-time-line text-lg"></i></span>
                            <input type="text"
                                class="form-control w-full pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="visa_timeline" name="visa_timeline"
                                value="{{ old('visa_timeline', $destination->visa_timeline ?? '') }}"
                                placeholder="e.g. 2-4 Weeks">
                        </div>
                    </div>

                    <!-- Work Rights -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="work_rights"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Post-Study Work
                            Rights</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-briefcase-line text-lg"></i></span>
                            <input type="text"
                                class="form-control w-full pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="work_rights" name="work_rights"
                                value="{{ old('work_rights', $destination->work_rights ?? '') }}"
                                placeholder="e.g. 2 Years">
                        </div>
                    </div>

                    <!-- Scholarships -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="scholarships_summary"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Scholarship
                            Availability</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors"><i
                                    class="ri-graduation-cap-line text-lg"></i></span>
                            <input type="text"
                                class="form-control w-full pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                id="scholarships_summary" name="scholarships_summary"
                                value="{{ old('scholarships_summary', $destination->scholarships_summary ?? '') }}"
                                placeholder="e.g. Merit-based available">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Media & Actions -->
    <div class="col-span-12 xl:col-span-4 space-y-6">

        <!-- Publish Action -->
        <div class="box shadow-sm">
            <div class="box-body p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex items-stretch gap-3 h-10">
                        <!-- Save Button -->
                        <button type="submit"
                            class="ti-btn ti-btn-primary flex-grow justify-center !text-base !py-0 !m-0 flex items-center">
                            <i class="ri-save-line mr-2"></i> Save Destination
                        </button>

                        <!-- Visibility Button (Square Tick) -->
                        <button type="button" id="visibility_btn"
                            class="flex items-center justify-center w-10 h-full rounded-md border transition-all {{ (old('is_active', $destination->is_active ?? 1) == 1) ? 'bg-primary border-primary text-white' : 'bg-gray-50 border-gray-200 text-gray-400 hover:border-primary hover:text-primary' }}"
                            title="Toggle Visibility">
                            <i
                                class="{{ (old('is_active', $destination->is_active ?? 1) == 1) ? 'ri-check-line text-xl font-bold' : 'ri-eye-off-line text-lg' }}"></i>
                        </button>
                        <input type="hidden" name="is_active" id="is_active_hidden"
                            value="{{ (old('is_active', $destination->is_active ?? 1) == 1) ? '1' : '0' }}">
                    </div>
                </div>

                <script>
                    const visibilityBtn = document.getElementById('visibility_btn');
                    const hiddenInput = document.getElementById('is_active_hidden');
                    const icon = visibilityBtn.querySelector('i');

                    visibilityBtn.addEventListener('click', function () {
                        const isVisible = hiddenInput.value === '1';
                        if (isVisible) {
                            // Switch to Hidden
                            hiddenInput.value = '0';
                            visibilityBtn.className = 'flex items-center justify-center w-10 h-full rounded-md border transition-all bg-gray-50 border-gray-200 text-gray-400 hover:border-primary hover:text-primary';
                            icon.className = 'ri-eye-off-line text-lg';
                        } else {
                            // Switch to Visible
                            hiddenInput.value = '1';
                            visibilityBtn.className = 'flex items-center justify-center w-10 h-full rounded-md border transition-all bg-primary border-primary text-white';
                            icon.className = 'ri-check-line text-xl font-bold';
                        }
                    });
                </script>
            </div>
        </div>

        <!-- Media Upload -->
        <div class="box shadow-sm">
            <div class="box-header !border-b-0 !pb-0">
                <div class="box-title text-sm font-semibold text-gray-800 dark:text-white">Cover Image</div>
            </div>
            <div class="box-body">
                <div class="flex flex-col items-center justify-center w-full mb-4">
                    @if(isset($destination) && $destination->image_url)
                        <div class="relative group w-full h-48 rounded-lg overflow-hidden border border-gray-200 mb-3">
                            <img src="{{ Str::startsWith($destination->image_url, 'http') ? $destination->image_url : Storage::url($destination->image_url) }}"
                                alt="Preview" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs">Current Cover</span>
                            </div>
                        </div>
                    @else
                        <div
                            class="w-full h-32 bg-gray-50 dark:bg-black/20 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center mb-3">
                            <i class="ri-image-add-line text-3xl text-gray-300"></i>
                            <span class="text-xs text-gray-400 mt-2">No Image Uploaded</span>
                        </div>
                    @endif

                    <label for="image_url"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                                    upload</span> or drag and drop</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX.
                                800x600px)</p>
                        </div>
                        <input id="image_url" name="image_url" type="file" class="hidden" accept="image/*" />
                    </label>
                </div>
            </div>
        </div>

        <!-- Entry Requirements (Moved to Sidebar) -->
        <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
            <div
                class="box-header !border-b !border-gray-100 dark:!border-white/10 !py-4 !px-5 bg-gray-50/50 dark:bg-white/5">
                <div class="box-title text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="ri-list-check-2 text-success"></i> Requirements
                </div>
            </div>
            <div class="box-body !p-6 space-y-4">
                <div>
                    <label for="entry_req_gpa"
                        class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2 tracking-wide">GPA</label>
                    <div class="relative w-full">
                        <textarea
                            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm"
                            id="entry_req_gpa" name="entry_req_gpa" rows="2"
                            placeholder="Score reqs...">{{ old('entry_req_gpa', $destination->entry_req_gpa ?? '') }}</textarea>
                    </div>
                </div>
                <div>
                    <label for="entry_req_language"
                        class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-2 tracking-wide">Language</label>
                    <div class="relative w-full">
                        <textarea
                            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm"
                            id="entry_req_language" name="entry_req_language" rows="2"
                            placeholder="IELTS/TOEFL reqs...">{{ old('entry_req_language', $destination->entry_req_language ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
