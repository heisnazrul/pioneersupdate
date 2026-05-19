@extends('layouts.admin')

@section('title', 'Add Certification')
@section('header', 'New Certification')

@section('content')
<div class="max-w-4xl" x-data="mediaPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.certifications.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Certifications
        </a>
    </div>

    <form action="{{ route('admin.certifications.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (English) *</label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (Arabic)</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle (English)</label>
                    <input type="text" name="subtitle_en" value="{{ old('subtitle_en') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle (Arabic)</label>
                    <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link (Optional)</label>
                <input type="url" name="link" value="{{ old('link') }}" placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image/Icon</label>
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
                        <p class="text-[10px] text-gray-500 italic" x-text="selectedUrl ? 'Selected' : 'No image selected'"></p>
                        <input type="hidden" name="image" :value="selectedPath">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Create Certification
            </button>
        </div>
    </form>

    <!-- Media Picker Modal -->
    @include('admin.partials.media-picker-modal', ['title' => 'Select Certification Image'])
</div>

<script>
function mediaPicker() {
    return {
        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        selectedId: null,
        tempSelected: null,
        selectedUrl: '{{ old("image") ? Storage::url(old("image")) : "" }}',
        selectedPath: '{{ old("image") ?? "" }}',
        uploadFile: null,
        uploadPreview: null,
        uploadTitle: '',

        openModal() { this.showModal = true; this.tab = 'gallery'; this.fetchImages(); },
        async fetchImages() {
            this.loading = true;
            try {
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=certification_image&search=${this.searchTerm}`);
                this.images = await response.json();
            } catch (e) { console.error(e); }
            this.loading = false;
        },
        selectImage(img) { this.selectedId = img.id; this.tempSelected = img; },
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
            formData.append('use_case', 'certification_image');
            formData.append('_token', '{{ csrf_token() }}');
            try {
                const response = await fetch(`{{ route('admin.galleries.api-store') }}`, { method: 'POST', body: formData });
                const result = await response.json();
                this.tempSelected = result;
                this.confirmSelection();
            } catch (e) { alert("Upload failed."); }
            this.uploading = false;
        },
        confirmSelection() {
            if (this.tempSelected) {
                this.selectedUrl = this.tempSelected.url;
                this.selectedPath = this.tempSelected.path;
                this.showModal = false;
            }
        }
    }
}
</script>
@endsection
