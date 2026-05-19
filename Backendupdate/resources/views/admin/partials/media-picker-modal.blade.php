<!-- Media Picker Modal -->
<div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden" @click.away="showModal = false">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title ?? 'Select Image' }}</h3>
            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Modal Body -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Main Gallery Area -->
            <div class="flex-1 flex flex-col p-6 overflow-hidden">
                <!-- Toolbar -->
                <div class="flex gap-4 mb-6">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" x-model="searchTerm" @input.debounce.300ms="fetchImages()" placeholder="Search images by title..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 bg-gray-50 dark:bg-gray-900 text-sm text-gray-900 dark:text-white">
                    </div>
                    <button @click="tab = 'upload'" :class="tab === 'upload' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload New
                    </button>
                </div>

                <!-- Images Grid -->
                <div x-show="tab === 'gallery'" class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-if="loading">
                        <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                            <i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2"></i>
                            <p>Loading images...</p>
                        </div>
                    </template>
                    <template x-if="!loading && images.length === 0">
                        <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                            <i class="fa-solid fa-image-slash text-2xl mb-2"></i>
                            <p>No images found matching your search.</p>
                        </div>
                    </template>
                    <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="img in images" :key="img.id">
                            <div @click="selectImage(img)" :class="selectedId === img.id ? 'border-primary-500 bg-primary-50' : 'border-gray-100 dark:border-gray-700 hover:border-primary-300'" class="relative aspect-square border-2 rounded-xl p-2 cursor-pointer transition-all group overflow-hidden bg-white dark:bg-gray-900">
                                <img :src="img.url" class="w-full h-full object-contain">
                                <div class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[9px] px-2 py-1 translate-y-full group-hover:translate-y-0 transition-transform truncate" x-text="img.title"></div>
                                <div x-show="selectedId === img.id" class="absolute top-1 right-1 bg-primary-600 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Upload Area -->
                <div x-show="tab === 'upload'" class="flex-1 flex flex-col items-center justify-center">
                    <div class="w-full max-w-md space-y-4">
                        <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center hover:border-primary-500 transition-colors relative">
                            <input type="file" @change="handleFileUpload" class="absolute inset-0 opacity-0 cursor-pointer">
                            <template x-if="!uploadFile">
                                <div>
                                    <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm text-gray-500">Click to select or drag image here</p>
                                </div>
                            </template>
                            <template x-if="uploadFile">
                                <div class="flex flex-col items-center">
                                    <img :src="uploadPreview" class="w-20 h-20 object-contain mb-2 border rounded p-1">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate w-full" x-text="uploadFile.name"></p>
                                </div>
                            </template>
                        </div>
                        <input type="text" x-model="uploadTitle" placeholder="Image Title (Optional)" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm text-gray-900 dark:text-white">
                        <div class="flex justify-between items-center pt-4">
                            <button type="button" @click="tab = 'gallery'" class="text-sm text-gray-500 hover:text-gray-700">Back to Gallery</button>
                            <button type="button" @click="uploadAndSelect()" :disabled="!uploadFile || uploading" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-50 transition-colors">
                                <span x-show="!uploading">Upload & Select</span>
                                <span x-show="uploading"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Uploading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50 dark:bg-gray-900">
            <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</button>
            <button type="button" @click="confirmSelection()" :disabled="!tempSelected" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-md hover:bg-primary-700 disabled:opacity-50 transition-colors">Confirm Selection</button>
        </div>
    </div>
</div>
