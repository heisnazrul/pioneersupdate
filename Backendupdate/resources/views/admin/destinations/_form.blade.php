@php($featureRows = collect(old('features', isset($destination) ? $destination->features->map->only(['feature', 'ar_feature'])->all() : []))->pad(5, ['feature' => '', 'ar_feature' => ''])->all())
@php($statRows = collect(old('stats', isset($destination) ? $destination->stats->map->only(['label', 'ar_label', 'value', 'ar_value'])->all() : []))->pad(4, ['label' => '', 'ar_label' => '', 'value' => '', 'ar_value' => ''])->all())
@php($intakeRows = collect(old('intakes', isset($destination) ? $destination->intakes->map->only(['month', 'ar_month', 'event', 'ar_event'])->all() : []))->pad(4, ['month' => '', 'ar_month' => '', 'event' => '', 'ar_event' => ''])->all())
@php($faqRows = collect(old('faqs', isset($destination) ? $destination->faqs->map->only(['question', 'ar_question', 'answer', 'ar_answer'])->all() : []))->pad(4, ['question' => '', 'ar_question' => '', 'answer' => '', 'ar_answer' => ''])->all())
@php($requirementRows = collect(old('requirements', isset($destination) ? $destination->requirements->map->only(['requirement', 'ar_requirement'])->all() : []))->pad(5, ['requirement' => '', 'ar_requirement' => ''])->all())

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
                <label class="block text-sm font-medium mb-1">Country</label>
                <select name="country_id" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Select country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ (string) old('country_id', $destination->country_id ?? '') === (string) $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $destination->name ?? '') }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $destination->slug ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Name</label>
                <input type="text" name="ar_name" value="{{ old('ar_name', $destination->ar_name ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Region</label>
                <input type="text" name="region" value="{{ old('region', $destination->region ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="space-y-3">
            <label class="block text-sm font-medium">Destination Image</label>
            <div class="w-full h-48 border-2 border-dashed rounded-xl flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                <template x-if="selectedUrl">
                    <img :src="selectedUrl" class="w-full h-full object-cover">
                </template>
                <template x-if="!selectedUrl">
                    <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                </template>
            </div>
            <button type="button" @click="openModal()" class="bg-white dark:bg-gray-700 border px-4 py-2 rounded-lg text-sm font-medium">Choose Image</button>
            <input type="hidden" name="gallery_image_url" :value="selectedPath">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('description', $destination->description ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Description</label>
                <textarea name="ar_description" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_description', $destination->ar_description ?? '') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Short Pitch</label>
                <textarea name="short_pitch" rows="3" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('short_pitch', $destination->short_pitch ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Short Pitch</label>
                <textarea name="ar_short_pitch" rows="3" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_short_pitch', $destination->ar_short_pitch ?? '') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Tuition Range</label>
                <input type="text" name="tuition_range" value="{{ old('tuition_range', $destination->tuition_range ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Visa Timeline</label>
                <input type="text" name="visa_timeline" value="{{ old('visa_timeline', $destination->visa_timeline ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Work Rights</label>
                <input type="text" name="work_rights" value="{{ old('work_rights', $destination->work_rights ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Tuition Range</label>
                <input type="text" name="ar_tuition_range" value="{{ old('ar_tuition_range', $destination->ar_tuition_range ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Visa Timeline</label>
                <input type="text" name="ar_visa_timeline" value="{{ old('ar_visa_timeline', $destination->ar_visa_timeline ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Work Rights</label>
                <input type="text" name="ar_work_rights" value="{{ old('ar_work_rights', $destination->ar_work_rights ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Scholarships Summary</label>
                <input type="text" name="scholarships_summary" value="{{ old('scholarships_summary', $destination->scholarships_summary ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Scholarships Summary</label>
                <input type="text" name="ar_scholarships_summary" value="{{ old('ar_scholarships_summary', $destination->ar_scholarships_summary ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">University Count</label>
                <input type="number" min="0" name="university_count" value="{{ old('university_count', $destination->university_count ?? 0) }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Region</label>
                <input type="text" name="ar_region" value="{{ old('ar_region', $destination->ar_region ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Entry Requirement GPA</label>
                <textarea name="entry_req_gpa" rows="3" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('entry_req_gpa', $destination->entry_req_gpa ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Entry Requirement GPA</label>
                <textarea name="ar_entry_req_gpa" rows="3" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_entry_req_gpa', $destination->ar_entry_req_gpa ?? '') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Entry Requirement Language</label>
                <textarea name="entry_req_language" rows="3" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('entry_req_language', $destination->entry_req_language ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Entry Requirement Language</label>
                <textarea name="ar_entry_req_language" rows="3" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_entry_req_language', $destination->ar_entry_req_language ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Features</h4>
        @foreach($featureRows as $index => $row)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="features[{{ $index }}][feature]" value="{{ $row['feature'] ?? '' }}" placeholder="Feature" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <input type="text" name="features[{{ $index }}][ar_feature]" value="{{ $row['ar_feature'] ?? '' }}" placeholder="Arabic feature" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Stats</h4>
        @foreach($statRows as $index => $row)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="stats[{{ $index }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="Label" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <input type="text" name="stats[{{ $index }}][ar_label]" value="{{ $row['ar_label'] ?? '' }}" placeholder="Arabic label" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
                <input type="text" name="stats[{{ $index }}][value]" value="{{ $row['value'] ?? '' }}" placeholder="Value" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <input type="text" name="stats[{{ $index }}][ar_value]" value="{{ $row['ar_value'] ?? '' }}" placeholder="Arabic value" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Intakes</h4>
        @foreach($intakeRows as $index => $row)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="intakes[{{ $index }}][month]" value="{{ $row['month'] ?? '' }}" placeholder="Month" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <input type="text" name="intakes[{{ $index }}][ar_month]" value="{{ $row['ar_month'] ?? '' }}" placeholder="Arabic month" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
                <input type="text" name="intakes[{{ $index }}][event]" value="{{ $row['event'] ?? '' }}" placeholder="Event" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <input type="text" name="intakes[{{ $index }}][ar_event]" value="{{ $row['ar_event'] ?? '' }}" placeholder="Arabic event" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">FAQs</h4>
        @foreach($faqRows as $index => $row)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <textarea name="faqs[{{ $index }}][question]" rows="2" placeholder="Question" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ $row['question'] ?? '' }}</textarea>
                <textarea name="faqs[{{ $index }}][ar_question]" rows="2" placeholder="Arabic question" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ $row['ar_question'] ?? '' }}</textarea>
                <textarea name="faqs[{{ $index }}][answer]" rows="3" placeholder="Answer" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ $row['answer'] ?? '' }}</textarea>
                <textarea name="faqs[{{ $index }}][ar_answer]" rows="3" placeholder="Arabic answer" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ $row['ar_answer'] ?? '' }}</textarea>
            </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Requirements</h4>
        @foreach($requirementRows as $index => $row)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="requirements[{{ $index }}][requirement]" value="{{ $row['requirement'] ?? '' }}" placeholder="Requirement" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <input type="text" name="requirements[{{ $index }}][ar_requirement]" value="{{ $row['ar_requirement'] ?? '' }}" placeholder="Arabic requirement" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        @endforeach
    </div>

    <label class="flex items-center gap-3 text-sm font-medium">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $destination->is_active ?? true) ? 'checked' : '' }}>
        Active
    </label>

    <div class="flex gap-3">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
        <a href="{{ route('admin.destinations.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
    </div>
</div>

@include('admin.partials.media-picker-modal', ['title' => 'Select Destination Image'])

<script>
function destinationMediaPicker() {
    return {
        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        selectedId: null,
        tempSelected: null,
        selectedUrl: '{{ old("gallery_image_url", $destination->image_url ?? "") ? Storage::url(old("gallery_image_url", $destination->image_url ?? "")) : "" }}',
        selectedPath: '{{ old("gallery_image_url", $destination->image_url ?? "") }}',
        uploadFile: null,
        uploadPreview: null,
        uploadTitle: '',
        openModal() {
            this.showModal = true;
            this.tab = 'gallery';
            this.fetchImages();
        },
        async fetchImages() {
            this.loading = true;
            const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=destination&search=${this.searchTerm}`);
            this.images = await response.json();
            this.loading = false;
        },
        selectImage(img) {
            this.selectedId = img.id;
            this.tempSelected = img;
        },
        handleFileUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.uploadFile = file;
            this.uploadPreview = URL.createObjectURL(file);
            if (!this.uploadTitle) this.uploadTitle = file.name.split('.')[0];
        },
        async uploadAndSelect() {
            this.uploading = true;
            const formData = new FormData();
            formData.append('image', this.uploadFile);
            formData.append('title', this.uploadTitle);
            formData.append('use_case', 'destination');
            formData.append('_token', '{{ csrf_token() }}');
            const response = await fetch(`{{ route('admin.galleries.api-store') }}`, { method: 'POST', body: formData });
            this.tempSelected = await response.json();
            this.confirmSelection();
            this.uploading = false;
        },
        confirmSelection() {
            if (!this.tempSelected) return;
            this.selectedUrl = this.tempSelected.url;
            this.selectedPath = this.tempSelected.path;
            this.showModal = false;
        }
    }
}
</script>
