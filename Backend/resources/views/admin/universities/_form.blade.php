@php
    $isEdit = isset($university) && $university->exists;
    $defaults = [
        'name' => old('name', $university->name ?? ''),
        'ar_name' => old('ar_name', $university->ar_name ?? ''),
        'slug' => old('slug', $university->slug ?? ''),
        'country_id' => old('country_id', $university->country_id ?? ''),
        'city_id' => old('city_id', $university->city_id ?? ''),
        'type' => old('type', $university->type ?? 'public'),
        'established_year' => old('established_year', $university->established_year ?? ''),
        'website' => old('website', $university->website ?? ''),
        'qs_ranking' => old('qs_ranking', $university->qs_ranking ?? ''),
        'the_ranking' => old('the_ranking', $university->the_ranking ?? ''),
        'shanghai_ranking' => old('shanghai_ranking', $university->shanghai_ranking ?? ''),
        'famous_for' => old('famous_for', $university->famous_for ?? ''),
        'ar_famous_for' => old('ar_famous_for', $university->ar_famous_for ?? ''),
        'fees' => old('fees', $university->fees ?? ''),
        'ar_fees' => old('ar_fees', $university->ar_fees ?? ''),
        'is_active' => old('is_active', ($university->is_active ?? true) ? '1' : '0'),
        'is_featured' => old('is_featured', ($university->is_featured ?? false) ? '1' : '0'),
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Basic Info -->
    <div class="md:col-span-2">
        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Basic Information</h3>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">University Name <span
                class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="{{ $defaults['name'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">University Name (Arabic)</label>
        <input type="text" name="ar_name" value="{{ $defaults['ar_name'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Slug (Auto)</label>
        <input type="text" id="slug" name="slug" value="{{ $defaults['slug'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2 bg-gray-50">
    </div>

    <!-- Location -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
        <select name="country_id" id="country_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            <option value="">Select Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" @selected($defaults['country_id'] == $country->id)>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
        <select name="city_id" id="city_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            <option value="">Select City</option>
            @if(isset($cities))
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" @selected($defaults['city_id'] == $city->id)>{{ $city->name }}</option>
                @endforeach
            @endif
        </select>
        <p class="text-xs text-gray-500 mt-1">Select a country first to load cities.</p>
    </div>

    <!-- Details -->
    <div>
        <label class="block text-sm font-medium text-gray-700">University Type <span
                class="text-red-500">*</span></label>
        <select name="type" class="mt-1 block w-full border rounded-md px-3 py-2">
            <option value="public" @selected($defaults['type'] == 'public')>Public</option>
            <option value="private" @selected($defaults['type'] == 'private')>Private</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Website URL</label>
        <input type="url" name="website" value="{{ $defaults['website'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" placeholder="https://example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Established Year</label>
        <input type="number" name="established_year" value="{{ $defaults['established_year'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" min="1000" max="{{ date('Y') }}">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">QS Ranking</label>
        <input type="number" name="qs_ranking" value="{{ $defaults['qs_ranking'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" min="1">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">THE Ranking</label>
        <input type="number" name="the_ranking" value="{{ $defaults['the_ranking'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" min="1">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Shanghai Ranking</label>
        <input type="number" name="shanghai_ranking" value="{{ $defaults['shanghai_ranking'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" min="1">
    </div>

    <!-- New Fields -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Famous For</label>
        <textarea name="famous_for" rows="2" class="mt-1 block w-full border rounded-md px-3 py-2"
            placeholder="e.g. Engineering, Medical Research...">{{ $defaults['famous_for'] }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Famous For (Arabic)</label>
        <textarea name="ar_famous_for" rows="2" class="mt-1 block w-full border rounded-md px-3 py-2"
            placeholder="...">{{ $defaults['ar_famous_for'] }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Fees Structure / Info</label>
        <textarea name="fees" rows="2" class="mt-1 block w-full border rounded-md px-3 py-2"
            placeholder="e.g. $10,000 - $20,000 avg yearly...">{{ $defaults['fees'] }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Fees (Arabic)</label>
        <textarea name="ar_fees" rows="2" class="mt-1 block w-full border rounded-md px-3 py-2"
            placeholder="...">{{ $defaults['ar_fees'] }}</textarea>
    </div>

    <!-- Images -->
    <div class="md:col-span-2">
        <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-4">Media</h3>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Logo</label>
        <input type="file" name="logo" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        @if($isEdit && $university->logo)
            <img src="{{ asset('storage/' . $university->logo) }}" class="mt-2 h-16 w-auto border rounded p-1">
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Cover Image</label>
        <input type="file" name="cover_image" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        @if($isEdit && $university->cover_image)
            <img src="{{ asset('storage/' . $university->cover_image) }}" class="mt-2 h-16 w-auto border rounded p-1">
        @endif
    </div>

    <!-- Switches -->
    <div class="md:col-span-2 flex items-center gap-6 mt-4">
        <div class="flex items-center">
            <input type="hidden" name="is_active" value="0">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1"
                    class="rounded border-gray-300 text-primary focus:ring-primary"
                    @checked($defaults['is_active'] === '1')>
                <span class="text-sm font-medium text-gray-700">Active</span>
            </label>
        </div>
        <div class="flex items-center">
            <input type="hidden" name="is_featured" value="0">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1"
                    class="rounded border-gray-300 text-amber-600 focus:ring-amber-600"
                    @checked($defaults['is_featured'] === '1')>
                <span class="text-sm font-medium text-gray-700">Featured</span>
            </label>
        </div>
    </div>
</div>

<div class="flex space-x-2 pt-8">
    <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
        {{ $isEdit ? 'Update University' : 'Save University' }}
    </button>
    <a href="{{ route('admin.universities.index') }}"
        class="inline-flex items-center px-4 py-2 border rounded-full">Cancel</a>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Slug generation
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (nameInput && slugInput) {
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
            }

            // Country - City AJAX
            const countrySelect = document.getElementById('country_id');
            const citySelect = document.getElementById('city_id');

            if (countrySelect && citySelect) {
                countrySelect.addEventListener('change', function () {
                    const countryId = this.value;
                    citySelect.innerHTML = '<option value="">Loading...</option>';

                    if (countryId) {
                        fetch(`{{ url('admin/universities/get-cities') }}/${countryId}`)
                            .then(response => response.json())
                            .then(data => {
                                citySelect.innerHTML = '<option value="">Select City</option>';
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.id;
                                    option.textContent = city.name;
                                    citySelect.appendChild(option);
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                citySelect.innerHTML = '<option value="">Error fetching cities</option>';
                            });
                    } else {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                    }
                });
            }
        });
    </script>
@endpush
