@php
    $isEdit = isset($category) && $category->exists;
    $defaults = [
        'name' => old('name', $category->name ?? ''),
        'ar_name' => old('ar_name', $category->ar_name ?? ''),
        'slug' => old('slug', $category->slug ?? ''),
        'description' => old('description', $category->description ?? ''),
        'ar_description' => old('ar_description', $category->ar_description ?? ''),
        'color' => old('color', $category->color ?? '#6366f1'),
        'display_order' => old('display_order', $category->display_order ?? 0),
        'is_active' => old('is_active', ($category->is_active ?? true) ? '1' : '0'),
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" value="{{ $defaults['name'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Name (AR)</label>
        <input type="text" name="ar_name" value="{{ $defaults['ar_name'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Slug (auto from Name, editable)</label>
        <input type="text" id="slug" name="slug" value="{{ $defaults['slug'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Color</label>
        <div class="flex items-center gap-3 mt-1">
            <input type="color" name="color" value="{{ $defaults['color'] }}" class="h-10 w-16 border rounded">
            <span class="text-sm text-gray-500">Used for badges or highlights.</span>
        </div>
    </div>

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

    <div>
        <label class="block text-sm font-medium text-gray-700">Display Order</label>
        <input type="number" name="display_order" min="0" value="{{ $defaults['display_order'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
        <p class="text-xs text-gray-500 mt-1">Lower numbers appear first.</p>
    </div>

    <div class="flex items-center md:col-span-2">
        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1"
                class="rounded border-gray-300 text-primary focus:ring-primary" @checked($defaults['is_active'] === '1')>
            <span class="text-sm text-gray-700">Active</span>
        </label>
        <span class="ml-3 text-xs text-gray-500">Inactive categories remain hidden from blog creation drop-downs.</span>
    </div>
</div>

<div class="flex space-x-2 pt-6">
    <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
        {{ $isEdit ? 'Update Category' : 'Save Category' }}
    </button>
    <a href="{{ route('admin.categories.index') }}"
        class="inline-flex items-center px-4 py-2 border rounded-full">Cancel</a>
</div>

@push('scripts')
    <script>
        (() => {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            if (!nameInput || !slugInput) {
                return;
            }

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