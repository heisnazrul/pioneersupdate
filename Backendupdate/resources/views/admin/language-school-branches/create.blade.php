@extends('layouts.admin')

@section('title', 'Add School Branch')
@section('header', 'New Branch')

@section('content')
<div class="max-w-4xl" x-data="branchPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.language-school-branches.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Branches
        </a>
    </div>

    <form action="{{ route('admin.language-school-branches.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School *</label>
                    <select name="school_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Searchable City Selection -->
                <div class="relative" x-data="{ open: false, search: '' }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City *</label>
                    <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white flex justify-between items-center">
                        <span x-text="selectedCityName || 'Search & Select City...'"></span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <input type="hidden" name="city_id" :value="selectedCityId" required>
                    
                    <div x-show="open" @click.away="open = false" class="absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl p-2" x-cloak>
                        <input type="text" x-model="search" placeholder="Filter cities..." class="w-full px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-md mb-2 bg-gray-50 dark:bg-gray-900">
                        <div class="max-h-48 overflow-y-auto custom-scrollbar">
                            @foreach($cities as $city)
                                <div x-show="'{{ strtolower($city->name) }} {{ strtolower($city->country->name) }}'.includes(search.toLowerCase())" 
                                     @click="selectedCityId = '{{ $city->id }}'; selectedCityName = '{{ $city->name }} ({{ $city->country->name }})'; open = false"
                                     class="px-3 py-2 text-sm hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer rounded-md transition-colors">
                                    {{ $city->name }} <span class="text-xs text-gray-400">({{ $city->country->name }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug (Optional)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="branch-slug" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Year Close From</label>
                    <input type="date" name="new_year_close_from" value="{{ old('new_year_close_from') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Year Close To</label>
                    <input type="date" name="new_year_close_to" value="{{ old('new_year_close_to') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <!-- Branch Images Multi-Picker -->
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Branch Images</label>
                <div class="p-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900/50">
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 mb-4">
                        <template x-for="(img, index) in selectedImages" :key="index">
                            <div class="relative aspect-square rounded-lg overflow-hidden border bg-white group">
                                <img :src="img.url" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage(index)" class="absolute top-1 right-1 bg-red-600 text-white w-5 h-5 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                </button>
                                <input type="hidden" name="gallery_images[]" :value="img.path">
                            </div>
                        </template>
                        <button type="button" @click="openModal()" class="aspect-square rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center text-gray-400 hover:text-primary-600 hover:border-primary-500 transition-all gap-1">
                            <i class="fa-solid fa-plus-circle text-xl"></i>
                            <span class="text-[10px] font-medium">Add Images</span>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Is Active? *</label>
                <select name="is_active" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="yes" {{ old('is_active') == 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ old('is_active') == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Create Branch
            </button>
        </div>
    </form>

    <!-- Multi Media Picker Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden" @click.away="showModal = false">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Select Branch Images</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="flex-1 flex overflow-hidden">
                <div class="flex-1 flex flex-col p-6 overflow-hidden">
                    <div class="flex gap-4 mb-6">
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" x-model="searchTerm" @input.debounce.300ms="fetchImages()" placeholder="Search branch images by title..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 bg-gray-50 dark:bg-gray-900 text-sm">
                        </div>
                        <button @click="tab = 'upload'" :class="tab === 'upload' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload New
                        </button>
                    </div>

                    <div x-show="tab === 'gallery'" class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-if="loading">
                            <div class="flex flex-col items-center justify-center h-40 text-gray-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2"></i><p>Loading...</p></div>
                        </template>
                        <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <template x-for="img in images" :key="img.id">
                                <div @click="toggleImage(img)" :class="isImageSelected(img.id) ? 'border-primary-500 bg-primary-50' : 'border-gray-100 hover:border-primary-300'" class="relative aspect-square border-2 rounded-xl p-2 cursor-pointer transition-all group overflow-hidden bg-white">
                                    <img :src="img.url" class="w-full h-full object-contain">
                                    <div class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[9px] px-2 py-1 translate-y-full group-hover:translate-y-0 transition-transform truncate" x-text="img.title"></div>
                                    <div x-show="isImageSelected(img.id)" class="absolute top-1 right-1 bg-primary-600 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md">
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
                                <template x-if="!uploadFile"><div><i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300 mb-2"></i><p class="text-sm text-gray-500">Click to select image</p></div></template>
                                <template x-if="uploadFile"><div class="flex flex-col items-center"><img :src="uploadPreview" class="w-20 h-20 object-contain mb-2 border rounded p-1"><p class="text-xs text-gray-600 truncate w-full" x-text="uploadFile.name"></p></div></template>
                            </div>
                            <input type="text" x-model="uploadTitle" placeholder="Image Title (Optional)" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm">
                            <div class="flex justify-between items-center pt-4">
                                <button type="button" @click="tab = 'gallery'" class="text-sm text-gray-500 hover:text-gray-700">Back to Gallery</button>
                                <button type="button" @click="uploadAndSelect()" :disabled="!uploadFile || uploading" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-50">
                                    <span x-show="!uploading">Upload & Add</span>
                                    <span x-show="uploading"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Uploading...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                <p class="text-xs text-gray-500"><span class="font-bold text-primary-600" x-text="tempSelected.length"></span> images selected</p>
                <div class="flex gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="button" @click="confirmSelection()" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-md hover:bg-primary-700">Add to Branch</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function branchPicker() {
    return {
        selectedCityId: '{{ old("city_id") ?? "" }}',
        selectedCityName: '', // Will be set by logic or Alpine init

        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        tempSelected: [], // Holds {id, path, url}
        selectedImages: [], // Final array in form

        uploadFile: null,
        uploadPreview: null,
        uploadTitle: '',

        openModal() {
            this.showModal = true;
            this.tab = 'gallery';
            this.tempSelected = [...this.selectedImages];
            this.fetchImages();
        },

        async fetchImages() {
            this.loading = true;
            try {
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=branch_image&search=${this.searchTerm}`);
                this.images = await response.json();
            } catch (e) { console.error(e); }
            this.loading = false;
        },

        toggleImage(img) {
            const index = this.tempSelected.findIndex(i => i.id === img.id);
            if (index > -1) {
                this.tempSelected.splice(index, 1);
            } else {
                this.tempSelected.push(img);
            }
        },

        isImageSelected(id) {
            return this.tempSelected.some(i => i.id === id);
        },

        removeImage(index) {
            this.selectedImages.splice(index, 1);
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
            formData.append('use_case', 'branch_image');
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch(`{{ route('admin.galleries.api-store') }}`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                // Add to temp selection so it's not lost
                this.tempSelected.push(result);
                // Then confirm everything
                this.confirmSelection();
                
                this.uploadFile = null;
                this.uploadTitle = '';
            } catch (e) { alert("Upload failed."); }
            this.uploading = false;
        },

        confirmSelection() {
            this.selectedImages = [...this.tempSelected];
            this.showModal = false;
        }
    }
}
</script>
@endsection
