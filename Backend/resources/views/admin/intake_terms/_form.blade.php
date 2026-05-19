<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-6">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Name <span
                class="text-red-500">*</span></label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="name" name="name" value="{{ old('name', $intakeTerm->name ?? '') }}" placeholder="e.g. Spring" required>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Key <span
                class="text-red-500">*</span></label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="key" name="key" value="{{ old('key', $intakeTerm->key ?? '') }}" placeholder="e.g. spring" required>
    </div>

    <div class="col-span-12 md:col-span-4">
        <label for="month_num" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Month
            (Optional)</label>
        <select name="month_num" id="month_num"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="">Select Month</option>
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ (old('month_num', $intakeTerm->month_num ?? '') == $i) ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 10)) }} ({{ $i }})
                </option>
            @endfor
        </select>
    </div>

    <div class="col-span-12 md:col-span-4">
        <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Sort
            Order</label>
        <input type="number"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="sort_order" name="sort_order" value="{{ old('sort_order', $intakeTerm->sort_order ?? 0) }}"
            placeholder="0">
    </div>

    <div class="col-span-12 md:col-span-4">
        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status</label>
        <select name="is_active" id="is_active"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="1" {{ old('is_active', $intakeTerm->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $intakeTerm->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>