@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700" for="code">Code</label>
        <input type="text" name="code" id="code" value="{{ old('code', $coupon->code ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('code')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $coupon->name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700" for="discount_type">Type</label>
        <select name="discount_type" id="discount_type" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
            @php $type = old('discount_type', $coupon->discount_type ?? 'percent'); @endphp
            <option value="percent" {{ $type === 'percent' ? 'selected' : '' }}>Percent</option>
            <option value="flat" {{ $type === 'flat' ? 'selected' : '' }}>Flat</option>
        </select>
        @error('discount_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="discount_value">Value</label>
        <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" value="{{ old('discount_value', $coupon->discount_value ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('discount_value')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="usage_limit">Usage Limit</label>
        <input type="number" min="1" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? 1) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('usage_limit')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700" for="used_count">Used Count</label>
        <input type="number" min="0" name="used_count" id="used_count" value="{{ old('used_count', $coupon->used_count ?? 0) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        @error('used_count')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="expiration_date">Expiration Date</label>
        <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', isset($coupon) && $coupon->expiration_date ? $coupon->expiration_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        @error('expiration_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="minimum_purchase_amount">Minimum Purchase</label>
        <input type="number" step="0.01" min="0" name="minimum_purchase_amount" id="minimum_purchase_amount" value="{{ old('minimum_purchase_amount', $coupon->minimum_purchase_amount ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        @error('minimum_purchase_amount')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="flex items-center gap-3 mt-4">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_active', isset($coupon) ? ($coupon->is_active ? '1' : '0') : '1') === '1' ? 'checked' : '' }}>
    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
</div>
