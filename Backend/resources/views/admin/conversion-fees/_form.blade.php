@csrf

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Base Currency</label>
        <input type="text" name="base_currency" value="{{ old('base_currency', $fee->base_currency ?? '') }}" maxlength="3" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Target Currency</label>
        <input type="text" name="target_currency" value="{{ old('target_currency', $fee->target_currency ?? '') }}" maxlength="3" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Fee (%)</label>
        <input type="number" step="0.01" min="0" name="fee" value="{{ old('fee', $fee->fee ?? 0) }}" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
</div>
