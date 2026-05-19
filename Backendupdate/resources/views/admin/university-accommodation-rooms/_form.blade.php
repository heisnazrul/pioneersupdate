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
                <label class="block text-sm font-medium mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title', $universityAccommodationRoom->title ?? '') }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Title</label>
                <input type="text" name="ar_title" value="{{ old('ar_title', $universityAccommodationRoom->ar_title ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $universityAccommodationRoom->slug ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="space-y-3">
            <label class="block text-sm font-medium">Room Image</label>
            <div class="w-full h-48 border-2 border-dashed rounded-xl flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                <template x-if="selectedUrl">
                    <img :src="selectedUrl" class="w-full h-full object-cover">
                </template>
                <template x-if="!selectedUrl">
                    <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                </template>
            </div>
            <button type="button" @click="openModal()" class="bg-white dark:bg-gray-700 border px-4 py-2 rounded-lg text-sm font-medium">Choose Image</button>
            <input type="hidden" name="gallery_image" :value="selectedPath">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('description', $universityAccommodationRoom->description ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Description</label>
                <textarea name="ar_description" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_description', $universityAccommodationRoom->ar_description ?? '') }}</textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Price</label>
            <input type="text" name="price" value="{{ old('price', $universityAccommodationRoom->price ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Features</label>
            <textarea name="features_text" rows="6" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" placeholder="One feature per line">{{ old('features_text', isset($universityAccommodationRoom) && is_array($universityAccommodationRoom->features) ? implode("\n", $universityAccommodationRoom->features) : '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Details</label>
                <textarea name="details" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('details', $universityAccommodationRoom->details ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Details</label>
                <textarea name="ar_details" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_details', $universityAccommodationRoom->ar_details ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
        <a href="{{ route('admin.university-accommodation-rooms.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
    </div>
</div>

@include('admin.partials.media-picker-modal', ['title' => 'Select Accommodation Room Image'])

<script>
function accommodationRoomMediaPicker() {
    return {
        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        selectedId: null,
        tempSelected: null,
        selectedUrl: '{{ old("gallery_image", $universityAccommodationRoom->image ?? "") ? Storage::url(old("gallery_image", $universityAccommodationRoom->image ?? "")) : "" }}',
        selectedPath: '{{ old("gallery_image", $universityAccommodationRoom->image ?? "") }}',
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
            const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=accommodation_room&search=${this.searchTerm}`);
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
            formData.append('use_case', 'accommodation_room');
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
