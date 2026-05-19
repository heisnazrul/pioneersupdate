@extends('layouts.admin')

@section('title', 'Edit Language School')
@section('header', 'Edit School')

@section('content')
<div class="max-w-4xl" x-data="mediaPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.language-schools.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Schools
        </a>
    </div>

    <form action="{{ route('admin.language-schools.update', $languageSchool) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Name (English) *</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $languageSchool->name_en) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Name (Arabic)</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $languageSchool->name_ar) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $languageSchool->slug) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">About (English)</label>
                    <textarea name="about_en" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('about_en', $languageSchool->about_en) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">About (Arabic)</label>
                    <textarea name="about_ar" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('about_ar', $languageSchool->about_ar) }}</textarea>
                </div>
            </div>

            <!-- Logo Section -->
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">School Logo</label>
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
                            <i class="fa-solid fa-images"></i> Change from Gallery
                        </button>
                        <p class="text-[10px] text-gray-500 italic" x-text="selectedUrl ? (selectedTitle ? 'Selected: ' + selectedTitle : 'Current Logo') : 'No logo selected'"></p>
                        <input type="hidden" name="gallery_logo" :value="selectedPath">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Has Online Courses? *</label>
                    <select name="has_online" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="no" {{ old('has_online', $languageSchool->has_online) == 'no' ? 'selected' : '' }}>No</option>
                        <option value="yes" {{ old('has_online', $languageSchool->has_online) == 'yes' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="active" {{ old('status', $languageSchool->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $languageSchool->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.language-schools.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Update School
            </button>
        </div>
    </form>

    <!-- Media Picker Modal (Same as create) -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden" @click.away="showModal = false">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Select School Logo</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="flex-1 flex overflow-hidden">
                <div class="flex-1 flex flex-col p-6 overflow-hidden">
                    <div class="flex gap-4 mb-6">
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" x-model="searchTerm" @input.debounce.300ms="fetchImages()" placeholder="Search logos by title..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 bg-gray-50 dark:bg-gray-900 text-sm">
                        </div>
                        <button @click="tab = 'upload'" :class="tab === 'upload' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload New
                        </button>
                    </div>
                    <div x-show="tab === 'gallery'" class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-if="loading">
                            <div class="flex flex-col items-center justify-center h-40 text-gray-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2"></i><p>Loading logos...</p></div>
                        </template>
                        <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <template x-for="img in images" :key="img.id">
                                <div @click="selectImage(img)" :class="selectedId === img.id || selectedPath === img.path ? 'border-primary-500 bg-primary-50' : 'border-gray-100 hover:border-primary-300'" class="relative aspect-square border-2 rounded-xl p-2 cursor-pointer transition-all group overflow-hidden bg-white">
                                    <img :src="img.url" class="w-full h-full object-contain">
                                    <div class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[9px] px-2 py-1 translate-y-full group-hover:translate-y-0 transition-transform truncate" x-text="img.title"></div>
                                    <div x-show="selectedId === img.id || selectedPath === img.path" class="absolute top-1 right-1 bg-primary-600 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div x-show="tab === 'upload'" class="flex-1 flex flex-col items-center justify-center">
                        <div class="w-full max-w-md space-y-4">
                            <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center hover:border-primary-500 transition-colors relative">
                                <input type="file" @change="handleFileUpload" class="absolute inset-0 opacity-0 cursor-pointer">
                                <template x-if="!uploadFile"><div><i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300 mb-2"></i><p class="text-sm text-gray-500">Click to select logo</p></div></template>
                                <template x-if="uploadFile"><div class="flex flex-col items-center"><img :src="uploadPreview" class="w-20 h-20 object-contain mb-2 border rounded p-1"><p class="text-xs text-gray-600 truncate w-full" x-text="uploadFile.name"></p></div></template>
                            </div>
                            <input type="text" x-model="uploadTitle" placeholder="Image Title (Optional)" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm">
                            <div class="flex justify-between items-center pt-4">
                                <button type="button" @click="tab = 'gallery'" class="text-sm text-gray-500 hover:text-gray-700">Back to Gallery</button>
                                <button type="button" @click="uploadAndSelect()" :disabled="!uploadFile || uploading" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-50">
                                    <span x-show="!uploading">Upload & Select</span>
                                    <span x-show="uploading"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Uploading...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50 dark:bg-gray-900">
                <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="button" @click="confirmSelection()" :disabled="!tempSelected" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-md hover:bg-primary-700 disabled:opacity-50">Confirm Selection</button>
            </div>
        </div>
    </div>
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
        
        // Final form values
        selectedUrl: '{{ $languageSchool->logo_url ? Storage::url($languageSchool->logo_url) : "" }}',
        selectedPath: '{{ $languageSchool->logo_url ?? "" }}',
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
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=school_logo&search=${this.searchTerm}`);
                this.images = await response.json();
            } catch (e) {
                console.error("Failed to fetch images", e);
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
            formData.append('use_case', 'school_logo');
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
                alert("Upload failed. Please try again.");
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
@endsection
