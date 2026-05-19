@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">General Information</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Language School *</label>
                    <select name="language_school_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('language_school_id', $course->language_school_id ?? '') == $school->id ? 'selected' : '' }}>
                                {{ $school->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Branch</label>
                    <select name="branch_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All Branches / Not Specific</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $course->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->school->name_en }} - {{ $branch->city->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-[10px] text-gray-400 italic">Optional. If selected, it must belong to the chosen school.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Type / Category *</label>
                    <select name="course_type_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select Type</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('course_type_id', $course->course_type_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $course->name ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Name</label>
                    <input type="text" name="ar_name" value="{{ old('ar_name', $course->ar_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('description', $course->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Description</label>
                    <textarea name="ar_description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_description', $course->ar_description ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Required Level</label>
                    <input type="text" name="required_level" value="{{ old('required_level', $course->required_level ?? '') }}" placeholder="A1 / B2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Study Time</label>
                    <input type="text" name="study_time" value="{{ old('study_time', $course->study_time ?? '') }}" placeholder="10h" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lessons/Week</label>
                    <input type="number" name="lessons_per_week" value="{{ old('lessons_per_week', $course->lessons_per_week ?? '') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Age</label>
                    <input type="number" name="min_age" value="{{ old('min_age', $course->min_age ?? '') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input type="text" name="start_date" value="{{ old('start_date', $course->start_date ?? '') }}" placeholder="Flexible" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Pricing & Media</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fee Type *</label>
                    <select name="fee_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="flat" {{ old('fee_type', $course->fee_type ?? 'flat') === 'flat' ? 'selected' : '' }}>Flat</option>
                        <option value="weekly" {{ old('fee_type', $course->fee_type ?? '') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fee Amount *</label>
                    <input type="number" step="0.01" name="fee_amount" value="{{ old('fee_amount', $course->fee_amount ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration Fee</label>
                    <input type="number" step="0.01" name="registration_fee" value="{{ old('registration_fee', $course->registration_fee ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course Thumbnail</label>
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                        <template x-if="selectedUrl">
                            <img :src="selectedUrl" class="max-w-full max-h-full object-contain">
                        </template>
                        <template x-if="!selectedUrl">
                            <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                        </template>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button type="button" @click="openModal()" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-images"></i> Choose from Gallery
                        </button>
                        <p class="text-[10px] text-gray-500 italic" x-text="selectedUrl ? (selectedTitle ? 'Selected: ' + selectedTitle : 'Thumbnail selected') : 'No thumbnail selected'"></p>
                        <input type="hidden" name="gallery_thumbnail" :value="selectedPath">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-4">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Publishing</h4>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="draft" {{ old('status', $course->status ?? 'published') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $course->status ?? 'published') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="suspended" {{ old('status', $course->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="visible" value="1" {{ old('visible', $course->visible ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                Visible on frontend
            </label>
        </div>

        <div class="flex flex-col gap-3">
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all text-lg">
                {{ $submitLabel }}
            </button>
            <a href="{{ route('admin.language-course-training-courses.index') }}" class="w-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 py-3 rounded-xl font-bold text-center transition-all">
                Cancel
            </a>
        </div>
    </div>
</div>

@include('admin.partials.media-picker-modal', ['title' => 'Select Training Course Thumbnail'])

<script>
function trainingMediaPicker() {
    return {
        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        selectedId: null,
        tempSelected: null,
        selectedUrl: '{{ old("gallery_thumbnail", $course->thumbnail ?? null) ? Storage::url(old("gallery_thumbnail", $course->thumbnail ?? null)) : "" }}',
        selectedPath: '{{ old("gallery_thumbnail", $course->thumbnail ?? "") }}',
        selectedTitle: '',
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
            try {
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=training&search=${this.searchTerm}`);
                this.images = await response.json();
            } catch (e) {
                console.error('Failed to fetch images', e);
            }
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
            formData.append('use_case', 'training');
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch(`{{ route('admin.galleries.api-store') }}`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                this.tempSelected = result;
                this.confirmSelection();
            } catch (e) {
                alert('Upload failed. Please try again.');
            }

            this.uploading = false;
        },

        confirmSelection() {
            if (this.tempSelected) {
                this.selectedUrl = this.tempSelected.url;
                this.selectedPath = this.tempSelected.path;
                this.selectedTitle = this.tempSelected.title;
                this.showModal = false;
            }
        }
    }
}
</script>
