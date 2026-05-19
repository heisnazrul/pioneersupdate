@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $pioneersDiscount->name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="ar_name">Arabic Name</label>
        <input type="text" name="ar_name" id="ar_name" value="{{ old('ar_name', $pioneersDiscount->ar_name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        @error('ar_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700" for="weeks">Weeks</label>
        <input type="number" min="1" name="weeks" id="weeks" value="{{ old('weeks', $pioneersDiscount->weeks ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        @error('weeks')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="discount_amount">Discount Amount</label>
        <input type="number" step="0.01" min="0" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $pioneersDiscount->discount_amount ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        @error('discount_amount')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700" for="discount_full_for">Full Discount For</label>
        <input type="text" name="discount_full_for" id="discount_full_for" value="{{ old('discount_full_for', $pioneersDiscount->discount_full_for ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        @error('discount_full_for')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="flex items-center gap-3 mt-4">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_active', isset($pioneersDiscount) ? ($pioneersDiscount->is_active ? '1' : '0') : '1') === '1' ? 'checked' : '' }}>
    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
</div>
