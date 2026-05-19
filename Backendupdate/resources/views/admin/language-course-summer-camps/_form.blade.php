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
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">General Information</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Branch *</label>
                    <select name="branch_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $camp->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->school->name_en }} - {{ $branch->city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Type / Category *</label>
                    <select name="course_type_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select Type</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('course_type_id', $camp->course_type_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Camp Name *</label>
                    <input type="text" name="name" value="{{ old('name', $camp->name ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Name</label>
                    <input type="text" name="ar_name" value="{{ old('ar_name', $camp->ar_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $camp->slug ?? '') }}" placeholder="Auto-generated if left blank" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('description', $camp->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Description</label>
                    <textarea name="ar_description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_description', $camp->ar_description ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Required Level</label>
                    <input type="text" name="required_level" value="{{ old('required_level', $camp->required_level ?? '') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Study Time</label>
                    <input type="text" name="study_time" value="{{ old('study_time', $camp->study_time ?? '') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lessons/Week</label>
                    <input type="number" name="lessons_per_week" value="{{ old('lessons_per_week', $camp->lessons_per_week ?? '') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age Range</label>
                    <input type="text" name="age_range" value="{{ old('age_range', $camp->age_range ?? '') }}" placeholder="8-17" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input type="text" name="start_date" value="{{ old('start_date', $camp->start_date ?? '') }}" placeholder="2026-07" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Pricing & Media</h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fee Type *</label>
                    <select name="fee_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="flat" {{ old('fee_type', $camp->fee_type ?? 'flat') === 'flat' ? 'selected' : '' }}>Flat</option>
                        <option value="weekly" {{ old('fee_type', $camp->fee_type ?? '') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fee Amount *</label>
                    <input type="number" step="0.01" name="fee_amount" value="{{ old('fee_amount', $camp->fee_amount ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration Fee</label>
                    <input type="number" step="0.01" name="registration_fee" value="{{ old('registration_fee', $camp->registration_fee ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Deadline</label>
                    <input type="date" name="payment_deadline" value="{{ old('payment_deadline', isset($camp->payment_deadline) ? $camp->payment_deadline?->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                            <template x-if="thumbnailUrl">
                                <img :src="thumbnailUrl" class="max-w-full max-h-full object-contain">
                            </template>
                            <template x-if="!thumbnailUrl">
                                <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                            </template>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button type="button" @click="openThumbnailModal()" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-images"></i> Choose from Gallery
                            </button>
                            <p class="text-[10px] text-gray-500 italic" x-text="thumbnailUrl ? 'Thumbnail selected' : 'No thumbnail selected'"></p>
                            <input type="hidden" name="gallery_thumbnail" :value="thumbnailPath">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Camp Images</label>
                <div class="p-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900/50">
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 mb-4">
                        <template x-for="(img, index) in selectedImages" :key="img.path + '-' + index">
                            <div class="relative aspect-square rounded-lg overflow-hidden border bg-white group">
                                <img :src="img.url" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage(index)" class="absolute top-1 right-1 bg-red-600 text-white w-5 h-5 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                </button>
                                <input type="hidden" name="gallery_images[]" :value="img.path">
                            </div>
                        </template>
                        <button type="button" @click="openImagesModal()" class="aspect-square rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center text-gray-400 hover:text-primary-600 hover:border-primary-500 transition-all gap-1">
                            <i class="fa-solid fa-plus-circle text-xl"></i>
                            <span class="text-[10px] font-medium">Add Images</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Camp Details</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Overview</label>
                    <textarea name="overview" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('overview', $detail->overview ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Overview</label>
                    <textarea name="ar_overview" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_overview', $detail->ar_overview ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academics</label>
                    <textarea name="academics" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('academics', $detail->academics ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Academics</label>
                    <textarea name="ar_academics" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_academics', $detail->ar_academics ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Activities</label>
                    <textarea name="activities" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('activities', $detail->activities ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Activities</label>
                    <textarea name="ar_activities" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_activities', $detail->ar_activities ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Accommodation</label>
                    <textarea name="accommodation" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('accommodation', $detail->accommodation ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Accommodation</label>
                    <textarea name="ar_accommodation" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_accommodation', $detail->ar_accommodation ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Safeguarding</label>
                    <textarea name="safeguarding" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('safeguarding', $detail->safeguarding ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arabic Safeguarding</label>
                    <textarea name="ar_safeguarding" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">{{ old('ar_safeguarding', $detail->ar_safeguarding ?? '') }}</textarea>
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
                    <option value="draft" {{ old('status', $camp->status ?? 'published') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $camp->status ?? 'published') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="suspended" {{ old('status', $camp->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="visible" value="1" {{ old('visible', $camp->visible ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                Visible on frontend
            </label>
        </div>

        <div class="flex flex-col gap-3">
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all text-lg">
                {{ $submitLabel }}
            </button>
            <a href="{{ route('admin.language-course-summer-camps.index') }}" class="w-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 py-3 rounded-xl font-bold text-center transition-all">
                Cancel
            </a>
        </div>
    </div>
</div>

<div x-show="thumbnailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden" @click.away="thumbnailModal = false">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Select Camp Thumbnail</h3>
            <button @click="thumbnailModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="flex-1 flex overflow-hidden">
            <div class="flex-1 flex flex-col p-6 overflow-hidden">
                <div class="flex gap-4 mb-6">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" x-model="thumbnailSearch" @input.debounce.300ms="fetchThumbnailImages()" placeholder="Search camp images by title..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 bg-gray-50 dark:bg-gray-900 text-sm">
                    </div>
                    <button @click="thumbnailTab = 'upload'" :class="thumbnailTab === 'upload' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload New
                    </button>
                </div>

                <div x-show="thumbnailTab === 'gallery'" class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-if="thumbnailLoading">
                        <div class="flex flex-col items-center justify-center h-40 text-gray-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2"></i><p>Loading images...</p></div>
                    </template>
                    <div x-show="!thumbnailLoading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="img in thumbnailImages" :key="img.id">
                            <div @click="selectThumbnail(img)" :class="thumbnailSelectedId === img.id ? 'border-primary-500 bg-primary-50' : 'border-gray-100 hover:border-primary-300'" class="relative aspect-square border-2 rounded-xl p-2 cursor-pointer transition-all group overflow-hidden bg-white">
                                <img :src="img.url" class="w-full h-full object-contain">
                                <div class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[9px] px-2 py-1 translate-y-full group-hover:translate-y-0 transition-transform truncate" x-text="img.title"></div>
                                <div x-show="thumbnailSelectedId === img.id" class="absolute top-1 right-1 bg-primary-600 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="thumbnailTab === 'upload'" class="flex-1 flex flex-col items-center justify-center">
                    <div class="w-full max-w-md space-y-4">
                        <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center hover:border-primary-500 transition-colors relative">
                            <input type="file" @change="handleThumbnailUpload" class="absolute inset-0 opacity-0 cursor-pointer">
                            <template x-if="!thumbnailUploadFile"><div><i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300 mb-2"></i><p class="text-sm text-gray-500">Click to select image</p></div></template>
                            <template x-if="thumbnailUploadFile"><div class="flex flex-col items-center"><img :src="thumbnailUploadPreview" class="w-20 h-20 object-contain mb-2 border rounded p-1"><p class="text-xs text-gray-600 truncate w-full" x-text="thumbnailUploadFile.name"></p></div></template>
                        </div>
                        <input type="text" x-model="thumbnailUploadTitle" placeholder="Image Title (Optional)" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm">
                        <div class="flex justify-between items-center pt-4">
                            <button type="button" @click="thumbnailTab = 'gallery'" class="text-sm text-gray-500 hover:text-gray-700">Back to Gallery</button>
                            <button type="button" @click="uploadThumbnailAndSelect()" :disabled="!thumbnailUploadFile || thumbnailUploading" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-50">
                                <span x-show="!thumbnailUploading">Upload & Select</span>
                                <span x-show="thumbnailUploading"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Uploading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50 dark:bg-gray-900">
            <button type="button" @click="thumbnailModal = false" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</button>
            <button type="button" @click="confirmThumbnail()" :disabled="!thumbnailTempSelected" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-md hover:bg-primary-700 disabled:opacity-50">Confirm Selection</button>
        </div>
    </div>
</div>

<div x-show="imagesModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden" @click.away="imagesModal = false">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Select Camp Images</h3>
            <button @click="imagesModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="flex-1 flex overflow-hidden">
            <div class="flex-1 flex flex-col p-6 overflow-hidden">
                <div class="flex gap-4 mb-6">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" x-model="imagesSearch" @input.debounce.300ms="fetchGalleryImages()" placeholder="Search camp images..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 bg-gray-50 dark:bg-gray-900 text-sm">
                    </div>
                    <button @click="imagesTab = 'upload'" :class="imagesTab === 'upload' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload New
                    </button>
                </div>

                <div x-show="imagesTab === 'gallery'" class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-if="imagesLoading">
                        <div class="flex flex-col items-center justify-center h-40 text-gray-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2"></i><p>Loading...</p></div>
                    </template>
                    <div x-show="!imagesLoading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="img in galleryImages" :key="img.id">
                            <div @click="toggleGalleryImage(img)" :class="isGalleryImageSelected(img.path) ? 'border-primary-500 bg-primary-50' : 'border-gray-100 hover:border-primary-300'" class="relative aspect-square border-2 rounded-xl p-2 cursor-pointer transition-all group overflow-hidden bg-white">
                                <img :src="img.url" class="w-full h-full object-contain">
                                <div class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[9px] px-2 py-1 translate-y-full group-hover:translate-y-0 transition-transform truncate" x-text="img.title"></div>
                                <div x-show="isGalleryImageSelected(img.path)" class="absolute top-1 right-1 bg-primary-600 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="imagesTab === 'upload'" class="flex-1 flex flex-col items-center justify-center">
                    <div class="w-full max-w-md space-y-4">
                        <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center hover:border-primary-500 transition-colors relative">
                            <input type="file" @change="handleImagesUpload" class="absolute inset-0 opacity-0 cursor-pointer">
                            <template x-if="!imagesUploadFile"><div><i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300 mb-2"></i><p class="text-sm text-gray-500">Click to select image</p></div></template>
                            <template x-if="imagesUploadFile"><div class="flex flex-col items-center"><img :src="imagesUploadPreview" class="w-20 h-20 object-contain mb-2 border rounded p-1"><p class="text-xs text-gray-600 truncate w-full" x-text="imagesUploadFile.name"></p></div></template>
                        </div>
                        <input type="text" x-model="imagesUploadTitle" placeholder="Image Title (Optional)" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm">
                        <div class="flex justify-between items-center pt-4">
                            <button type="button" @click="imagesTab = 'gallery'" class="text-sm text-gray-500 hover:text-gray-700">Back to Gallery</button>
                            <button type="button" @click="uploadImageAndAdd()" :disabled="!imagesUploadFile || imagesUploading" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-50">
                                <span x-show="!imagesUploading">Upload & Add</span>
                                <span x-show="imagesUploading"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Uploading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
            <p class="text-xs text-gray-500"><span class="font-bold text-primary-600" x-text="tempImages.length"></span> images selected</p>
            <div class="flex gap-3">
                <button type="button" @click="imagesModal = false" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="button" @click="confirmImages()" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-md hover:bg-primary-700">Add to Camp</button>
            </div>
        </div>
    </div>
</div>

<script>
function summerCampForm() {
    return {
        thumbnailModal: false,
        thumbnailTab: 'gallery',
        thumbnailLoading: false,
        thumbnailUploading: false,
        thumbnailSearch: '',
        thumbnailImages: [],
        thumbnailSelectedId: null,
        thumbnailTempSelected: null,
        thumbnailUrl: '{{ old("gallery_thumbnail", $camp->thumbnail ?? null) ? Storage::url(old("gallery_thumbnail", $camp->thumbnail ?? null)) : "" }}',
        thumbnailPath: '{{ old("gallery_thumbnail", $camp->thumbnail ?? "") }}',
        thumbnailUploadFile: null,
        thumbnailUploadPreview: null,
        thumbnailUploadTitle: '',

        imagesModal: false,
        imagesTab: 'gallery',
        imagesLoading: false,
        imagesUploading: false,
        imagesSearch: '',
        galleryImages: [],
        tempImages: [],
        selectedImages: [
            @foreach(old('gallery_images', $detail->images ?? []) as $image)
                { path: '{{ $image }}', url: '{{ Storage::url($image) }}' },
            @endforeach
        ],
        imagesUploadFile: null,
        imagesUploadPreview: null,
        imagesUploadTitle: '',

        openThumbnailModal() {
            this.thumbnailModal = true;
            this.thumbnailTab = 'gallery';
            this.fetchThumbnailImages();
        },

        async fetchThumbnailImages() {
            this.thumbnailLoading = true;
            try {
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=camps&search=${this.thumbnailSearch}`);
                this.thumbnailImages = await response.json();
            } catch (e) {
                console.error(e);
            }
            this.thumbnailLoading = false;
        },

        selectThumbnail(img) {
            this.thumbnailSelectedId = img.id;
            this.thumbnailTempSelected = img;
        },

        handleThumbnailUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.thumbnailUploadFile = file;
            this.thumbnailUploadPreview = URL.createObjectURL(file);
            if (!this.thumbnailUploadTitle) this.thumbnailUploadTitle = file.name.split('.')[0];
        },

        async uploadThumbnailAndSelect() {
            this.thumbnailUploading = true;
            const formData = new FormData();
            formData.append('image', this.thumbnailUploadFile);
            formData.append('title', this.thumbnailUploadTitle);
            formData.append('use_case', 'camps');
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch(`{{ route('admin.galleries.api-store') }}`, { method: 'POST', body: formData });
                const result = await response.json();
                this.thumbnailTempSelected = result;
                this.confirmThumbnail();
            } catch (e) {
                alert('Upload failed.');
            }
            this.thumbnailUploading = false;
        },

        confirmThumbnail() {
            if (this.thumbnailTempSelected) {
                this.thumbnailUrl = this.thumbnailTempSelected.url;
                this.thumbnailPath = this.thumbnailTempSelected.path;
                this.thumbnailModal = false;
            }
        },

        openImagesModal() {
            this.imagesModal = true;
            this.imagesTab = 'gallery';
            this.tempImages = [...this.selectedImages];
            this.fetchGalleryImages();
        },

        async fetchGalleryImages() {
            this.imagesLoading = true;
            try {
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=camps&search=${this.imagesSearch}`);
                this.galleryImages = await response.json();
            } catch (e) {
                console.error(e);
            }
            this.imagesLoading = false;
        },

        toggleGalleryImage(img) {
            const index = this.tempImages.findIndex(i => i.path === img.path);
            if (index > -1) {
                this.tempImages.splice(index, 1);
            } else {
                this.tempImages.push(img);
            }
        },

        isGalleryImageSelected(path) {
            return this.tempImages.some(i => i.path === path);
        },

        removeImage(index) {
            this.selectedImages.splice(index, 1);
        },

        handleImagesUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.imagesUploadFile = file;
            this.imagesUploadPreview = URL.createObjectURL(file);
            if (!this.imagesUploadTitle) this.imagesUploadTitle = file.name.split('.')[0];
        },

        async uploadImageAndAdd() {
            this.imagesUploading = true;
            const formData = new FormData();
            formData.append('image', this.imagesUploadFile);
            formData.append('title', this.imagesUploadTitle);
            formData.append('use_case', 'camps');
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch(`{{ route('admin.galleries.api-store') }}`, { method: 'POST', body: formData });
                const result = await response.json();
                this.tempImages.push(result);
                this.confirmImages();
            } catch (e) {
                alert('Upload failed.');
            }
            this.imagesUploading = false;
        },

        confirmImages() {
            this.selectedImages = [...this.tempImages];
            this.imagesModal = false;
        }
    }
}
</script>
