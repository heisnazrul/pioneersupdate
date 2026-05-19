@php
    $isEdit = isset($country) && $country->exists;
    $defaults = [
        'name' => old('name', $country->name ?? ''),
        'ar_name' => old('ar_name', $country->ar_name ?? ''),
        'slug' => old('slug', $country->slug ?? ''),
        'country_code' => old('country_code', $country->country_code ?? ''),
        'currency_code' => old('currency_code', $country->currency_code ?? ''),
        'phone_code' => old('phone_code', $country->phone_code ?? ''),
        'capital' => old('capital', $country->capital ?? ''),
        'continent' => old('continent', $country->continent ?? ''),
        'description' => old('description', $country->description ?? ''),
        'ar_description' => old('ar_description', $country->ar_description ?? ''),
        'display_order' => old('display_order', $country->display_order ?? 0),
        'is_active' => old('is_active', ($country->is_active ?? true) ? '1' : '0'),
        'is_popular' => old('is_popular', ($country->is_popular ?? false) ? '1' : '0'),
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Identity -->
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

    <!-- Codes -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Country Code (ISO 2-char) <span
                class="text-red-500">*</span></label>
        <input type="text" name="country_code" value="{{ $defaults['country_code'] }}" maxlength="10"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Currency Code <span class="text-red-500">*</span></label>
        <input type="text" name="currency_code" value="{{ $defaults['currency_code'] }}" maxlength="10"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <!-- Details -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Phone Code</label>
        <input type="text" name="phone_code" value="{{ $defaults['phone_code'] }}" maxlength="10"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Display Order</label>
        <input type="number" name="display_order" min="0" value="{{ $defaults['display_order'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Capital</label>
        <input type="text" name="capital" value="{{ $defaults['capital'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Continent</label>
        <input type="text" name="continent" value="{{ $defaults['continent'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <!-- Slug -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Slug (Auto-generated)</label>
        <input type="text" id="slug" name="slug" value="{{ $defaults['slug'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2 bg-gray-50">
    </div>

    <!-- Flag -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Flag Image</label>
        <input type="file" name="flag_upload" accept="image/*,.svg"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        @if($isEdit && $country->flag)
            <div class="mt-2 flex items-center gap-4">
                <img src="{{ asset('storage/' . $country->flag) }}" alt="Current Flag" class="h-8 w-auto">
                <label class="inline-flex items-center text-sm text-red-600">
                    <input type="checkbox" name="remove_flag" value="1" class="mr-2"> Remove Flag
                </label>
            </div>
        @endif
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

    <!-- Switches -->
    <div class="flex items-center gap-6 md:col-span-2">
        <!-- Active -->
        <div class="flex items-center">
            <input type="hidden" name="is_active" value="0">
            <label class="inline-flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1"
                    class="rounded border-gray-300 text-primary focus:ring-primary"
                    @checked($defaults['is_active'] === '1')>
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>

        <!-- Popular -->
        <div class="flex items-center">
            <input type="hidden" name="is_popular" value="0">
            <label class="inline-flex items-center gap-3">
                <input type="checkbox" name="is_popular" value="1"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                    @checked($defaults['is_popular'] === '1')>
                <span class="text-sm text-gray-700">Popular Destination</span>
            </label>
        </div>
    </div>
</div>

<div class="flex space-x-2 pt-6">
    <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
        {{ $isEdit ? 'Update Country' : 'Save Country' }}
    </button>
    <a href="{{ route('admin.countries.index') }}"
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