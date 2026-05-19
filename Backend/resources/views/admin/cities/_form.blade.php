@php
    $isEdit = isset($city) && $city->exists;
    $defaults = [
        'name' => old('name', $city->name ?? ''),
        'ar_name' => old('ar_name', $city->ar_name ?? ''),
        'slug' => old('slug', $city->slug ?? ''),
        'country_id' => old('country_id', $city->country_id ?? ''),
        'description' => old('description', $city->description ?? ''),
        'ar_description' => old('ar_description', $city->ar_description ?? ''),
        'latitude' => old('latitude', $city->latitude ?? ''),
        'longitude' => old('longitude', $city->longitude ?? ''),
        'display_order' => old('display_order', $city->display_order ?? 0),
        'is_active' => old('is_active', ($city->is_active ?? true) ? '1' : '0'),
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Naming -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="{{ $defaults['name'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Name (AR) <span class="text-red-500">*</span></label>
        <input type="text" name="ar_name" value="{{ $defaults['ar_name'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <!-- Country -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
        <select name="country_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            <option value="">Select Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" @selected($defaults['country_id'] == $country->id)>
                    {{ $country->name }} ({{ $country->country_code }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Slug -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Slug (Auto-generated)</label>
        <input type="text" id="slug" name="slug" value="{{ $defaults['slug'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2 bg-gray-50">
    </div>

    <!-- Order -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Display Order</label>
        <input type="number" name="display_order" min="0" value="{{ $defaults['display_order'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <!-- Location -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Latitude</label>
        <input type="text" name="latitude" value="{{ $defaults['latitude'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" placeholder="e.g. 51.5074">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Longitude</label>
        <input type="text" name="longitude" value="{{ $defaults['longitude'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" placeholder="e.g. -0.1278">
    </div>

    <!-- Descriptions -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="3"
            class="mt-1 block w-full border rounded-md px-3 py-2">{{ $defaults['description'] }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
        <textarea name="ar_description" rows="3"
            class="mt-1 block w-full border rounded-md px-3 py-2">{{ $defaults['ar_description'] }}</textarea>
    </div>

    <!-- Active Switch -->
    <div class="flex items-center md:col-span-2">
        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1"
                class="rounded border-gray-300 text-primary focus:ring-primary" @checked($defaults['is_active'] === '1')>
            <span class="text-sm text-gray-700">Active</span>
        </label>
    </div>
</div>

<div class="flex space-x-2 pt-6">
    <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
        {{ $isEdit ? 'Update City' : 'Save City' }}
    </button>
    <a href="{{ route('admin.cities.index') }}"
        class="inline-flex items-center px-4 py-2 border rounded-full">Cancel</a>
</div>

@push('scripts')
    <script>
        (() => {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            if (!nameInput || !slugInput) return;

            const slugify = (text) => text.toString().toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)+/g, '')
                .substring(0, 255);

            nameInput.addEventListener('input', () => {
                if (!slugInput.dataset.touched) {
                    slugInput.value = slugify(nameInput.value);
                }
            });

            slugInput.addEventListener('input', () => {
                slugInput.dataset.touched = '1';
            });
        })();
    </script>
@endpush