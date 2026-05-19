@php
    $inputClass = "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2";
    $labelClass = "block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1";
    $errorClass = "text-red-500 text-sm mt-1";
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Room Details</h3>
            
            <div class="space-y-4">
                <div>
                    <label for="title" class="{{ $labelClass }}">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="{{ $inputClass }}" value="{{ old('title', $accommodationRoom->title ?? '') }}" required>
                    @error('title')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ar_title" class="{{ $labelClass }}">Title (Arabic)</label>
                    <input type="text" name="ar_title" id="ar_title" class="{{ $inputClass }}" value="{{ old('ar_title', $accommodationRoom->ar_title ?? '') }}">
                </div>

                <div>
                    <label for="slug" class="{{ $labelClass }}">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" id="slug" class="{{ $inputClass }}" value="{{ old('slug', $accommodationRoom->slug ?? '') }}" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Unique identifier for URL (e.g., en-suite-room)</p>
                    @error('slug')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="{{ $labelClass }}">Price Label</label>
                    <input type="text" name="price" id="price" class="{{ $inputClass }}" value="{{ old('price', $accommodationRoom->price ?? '') }}" placeholder="e.g. From £150/week">
                    @error('price')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="{{ $labelClass }}">Short Description</label>
                    <textarea name="description" id="description" rows="3" class="{{ $inputClass }}">{{ old('description', $accommodationRoom->description ?? '') }}</textarea>
                    @error('description')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ar_description" class="{{ $labelClass }}">Short Description (Arabic)</label>
                    <textarea name="ar_description" id="ar_description" rows="3" class="{{ $inputClass }}">{{ old('ar_description', $accommodationRoom->ar_description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Features Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Key Features</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">List up to 5 key features to display on the card.</p>

            <div class="space-y-3">
                @php
                    $features = old('features', $accommodationRoom->features ?? []);
                @endphp
                @for($i = 0; $i < 5; $i++)
                    <div>
                        <input type="text" name="features[]" class="{{ $inputClass }}" value="{{ $features[$i] ?? '' }}" placeholder="Feature {{ $i + 1 }}">
                    </div>
                @endfor
                @error('features')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Full Details</h3>
            <div>
                 <textarea name="details" id="details" rows="15" class="{{ $inputClass }}">{{ old('details', $accommodationRoom->details ?? '') }}</textarea>
                 <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">HTML is allowed for rich content.</p>
                 @error('details')
                     <p class="{{ $errorClass }}">{{ $message }}</p>
                 @enderror
             </div>
            <div class="mt-4">
                <label for="ar_details" class="{{ $labelClass }}">Full Details (Arabic)</label>
                <textarea name="ar_details" id="ar_details" rows="15" class="{{ $inputClass }}">{{ old('ar_details', $accommodationRoom->ar_details ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <!-- Image Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Room Image</h3>
            
            <div class="space-y-4">
                @if(isset($accommodationRoom) && $accommodationRoom->image)
                    <div class="mb-4">
                        <label class="{{ $labelClass }}">Current Image</label>
                        <div class="border dark:border-gray-600 rounded-md p-2 bg-gray-50 dark:bg-gray-900/50">
                            <img src="{{ $accommodationRoom->image }}" alt="Current Image" class="w-full h-auto rounded">
                        </div>
                    </div>
                @endif

                <div>
                    <label for="image" class="{{ $labelClass }}">Upload New Image</label>
                    <input type="file" name="image" id="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-400">
                    @error('image')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <button type="submit" class="w-full ti-btn ti-btn-primary mb-3 justify-center">
                Save Room
            </button>
            <a href="{{ route('admin.accommodation-rooms.index') }}" class="w-full ti-btn ti-btn-outline ti-btn-outline-secondary justify-center block text-center">
                Cancel
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple slug generator
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if(titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')) {
                 slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            }
        });
    }
</script>
@endpush
