@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="form-group">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $discount->name ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label for="ar_name" class="block text-sm font-medium text-gray-700">Arabic Name</label>
        <input type="text" name="ar_name" id="ar_name" value="{{ old('ar_name', $discount->ar_name ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('ar_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="form-group">
        <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Discount Percentage</label>
        <input type="number" step="0.01" min="0" max="1000" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', $discount->discount_percentage ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        <p class="text-xs text-gray-500 mt-1">Example: 20 = 20%.</p>
        @error('discount_percentage')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($discount) && $discount->start_date ? $discount->start_date->format('Y-m-d') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('start_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($discount) && $discount->end_date ? $discount->end_date->format('Y-m-d') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('end_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="form-group">
        <label for="school_branch_ids" class="block text-sm font-medium text-gray-700">Limit to Branches</label>
        <select name="school_branch_ids[]" id="school_branch_ids" multiple class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @php
                $selectedBranches = collect(old('school_branch_ids', $discount->school_branch_ids ?? []))->map(fn($v) => (int) $v)->all();
            @endphp
            @foreach($branches as $branch)
                @php
                    $labelParts = [];
                    if ($branch->school?->name) { $labelParts[] = $branch->school->name; }
                    if ($branch->city?->name) { $labelParts[] = $branch->city->name; }
                    $labelParts[] = 'ID: '.$branch->id;
                    $label = implode(' · ', $labelParts);
                @endphp
                <option value="{{ $branch->id }}" {{ in_array($branch->id, $selectedBranches, true) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Leave empty to apply to all branches or use the flag below.</p>
        @error('school_branch_ids')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label for="country_ids" class="block text-sm font-medium text-gray-700">Limit to Countries</label>
        <select name="country_ids[]" id="country_ids" multiple class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @php
                $selectedCountries = collect(old('country_ids', $discount->country_ids ?? []))->map(fn($v) => (int) $v)->all();
            @endphp
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ in_array($country->id, $selectedCountries, true) ? 'selected' : '' }}>
                    {{ $country->name }} (ID: {{ $country->id }})
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Leave empty to apply to all countries or use the flag below.</p>
        @error('country_ids')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
    <div class="flex items-center gap-3">
        <input type="hidden" name="applies_to_all_branches" value="0">
        <input type="checkbox" name="applies_to_all_branches" value="1" id="applies_to_all_branches" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('applies_to_all_branches', isset($discount) ? ($discount->applies_to_all_branches ? '1' : '0') : '1') === '1' ? 'checked' : '' }}>
        <label for="applies_to_all_branches" class="text-sm font-medium text-gray-700">Applies to all branches</label>
    </div>
    <div class="flex items-center gap-3">
        <input type="hidden" name="applies_to_all_countries" value="0">
        <input type="checkbox" name="applies_to_all_countries" value="1" id="applies_to_all_countries" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('applies_to_all_countries', isset($discount) ? ($discount->applies_to_all_countries ? '1' : '0') : '1') === '1' ? 'checked' : '' }}>
        <label for="applies_to_all_countries" class="text-sm font-medium text-gray-700">Applies to all countries</label>
    </div>
    <div class="flex items-center gap-3">
        <input type="hidden" name="applies_to_user_country" value="0">
        <input type="checkbox" name="applies_to_user_country" value="1" id="applies_to_user_country" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('applies_to_user_country', isset($discount) ? ($discount->applies_to_user_country ? '1' : '0') : '0') === '1' ? 'checked' : '' }}>
        <label for="applies_to_user_country" class="text-sm font-medium text-gray-700">Applies to user country</label>
    </div>
</div>

<div class="flex items-center gap-3 mt-4">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_active', isset($discount) ? ($discount->is_active ? '1' : '0') : '1') === '1' ? 'checked' : '' }}>
    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
</div>
