@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $university->name ?? '') }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Arabic Name</label>
                    <input type="text" name="ar_name" value="{{ old('ar_name', $university->ar_name ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $university->slug ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Type *</label>
                    <select name="type" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                        <option value="public" {{ old('type', $university->type ?? 'public') === 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ old('type', $university->type ?? '') === 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Established Year</label>
                    <input type="number" name="established_year" value="{{ old('established_year', $university->established_year ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Country *</label>
                    <select name="country_id" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                        <option value="">Select country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ (string) old('country_id', $university->country_id ?? '') === (string) $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">City *</label>
                    <select name="city_id" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                        <option value="">Select city</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ (string) old('city_id', $university->city_id ?? '') === (string) $city->id ? 'selected' : '' }}>{{ $city->name }} ({{ $city->country?->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $university->website ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">QS Ranking</label>
                    <input type="number" name="qs_ranking" value="{{ old('qs_ranking', $university->qs_ranking ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">THE Ranking</label>
                    <input type="number" name="the_ranking" value="{{ old('the_ranking', $university->the_ranking ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Shanghai Ranking</label>
                    <input type="number" name="shanghai_ranking" value="{{ old('shanghai_ranking', $university->shanghai_ranking ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Famous For</label>
                    <textarea name="famous_for" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('famous_for', $university->famous_for ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Arabic Famous For</label>
                    <textarea name="ar_famous_for" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_famous_for', $university->ar_famous_for ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Fees</label>
                    <textarea name="fees" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('fees', $university->fees ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Arabic Fees</label>
                    <textarea name="ar_fees" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_fees', $university->ar_fees ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
            <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Media</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <label class="block text-sm font-medium">Logo</label>
                    <div class="w-28 h-28 border-2 border-dashed rounded-xl flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                        <template x-if="fields.logo.url">
                            <img :src="fields.logo.url" class="w-full h-full object-contain">
                        </template>
                        <template x-if="!fields.logo.url">
                            <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                        </template>
                    </div>
                    <button type="button" @click="openModal('logo', 'university_logo')" class="bg-white dark:bg-gray-700 border px-4 py-2 rounded-lg text-sm font-medium">Choose Logo</button>
                    <input type="hidden" name="gallery_logo" :value="fields.logo.path">
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-medium">Cover Image</label>
                    <div class="w-full h-28 border-2 border-dashed rounded-xl flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                        <template x-if="fields.cover.url">
                            <img :src="fields.cover.url" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!fields.cover.url">
                            <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                        </template>
                    </div>
                    <button type="button" @click="openModal('cover', 'university_cover')" class="bg-white dark:bg-gray-700 border px-4 py-2 rounded-lg text-sm font-medium">Choose Cover</button>
                    <input type="hidden" name="gallery_cover_image" :value="fields.cover.path">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-4">
            <label class="flex items-center gap-3 text-sm font-medium">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $university->is_active ?? true) ? 'checked' : '' }}>
                Active
            </label>
            <label class="flex items-center gap-3 text-sm font-medium">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $university->is_featured ?? false) ? 'checked' : '' }}>
                Featured
            </label>
        </div>

        <div class="flex flex-col gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
            <a href="{{ route('admin.universities.index') }}" class="bg-gray-100 dark:bg-gray-700 py-3 rounded-lg text-center font-medium">Cancel</a>
        </div>
    </div>
</div>

@include('admin.partials.media-picker-modal', ['title' => 'Select University Image'])

<script>
function universityMediaPicker() {
    return {
        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        selectedId: null,
        tempSelected: null,
        activeField: 'logo',
        activeUseCase: 'university_logo',
        fields: {
            logo: {
                url: '{{ old("gallery_logo", $university->logo ?? "") ? Storage::url(old("gallery_logo", $university->logo ?? "")) : "" }}',
                path: '{{ old("gallery_logo", $university->logo ?? "") }}',
                title: ''
            },
            cover: {
                url: '{{ old("gallery_cover_image", $university->cover_image ?? "") ? Storage::url(old("gallery_cover_image", $university->cover_image ?? "")) : "" }}',
                path: '{{ old("gallery_cover_image", $university->cover_image ?? "") }}',
                title: ''
            }
        },
        uploadFile: null,
        uploadPreview: null,
        uploadTitle: '',

        openModal(field, useCase) {
            this.activeField = field;
            this.activeUseCase = useCase;
            this.showModal = true;
            this.tab = 'gallery';
            this.fetchImages();
        },
        async fetchImages() {
            this.loading = true;
            const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=${this.activeUseCase}&search=${this.searchTerm}`);
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
            formData.append('use_case', this.activeUseCase);
            formData.append('_token', '{{ csrf_token() }}');
            const response = await fetch(`{{ route('admin.galleries.api-store') }}`, { method: 'POST', body: formData });
            this.tempSelected = await response.json();
            this.confirmSelection();
            this.uploading = false;
        },
        confirmSelection() {
            if (!this.tempSelected) return;
            this.fields[this.activeField].url = this.tempSelected.url;
            this.fields[this.activeField].path = this.tempSelected.path;
            this.fields[this.activeField].title = this.tempSelected.title;
            this.showModal = false;
        }
    }
}
</script>
