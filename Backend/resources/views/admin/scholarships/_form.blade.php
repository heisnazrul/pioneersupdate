<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-6">
        <label for="university_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">University /
            Provider</label>
        <select name="university_id" id="university_id"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="">No University (Third Party)</option>
            @foreach($universities as $uni)
                <option value="{{ $uni->id }}" {{ old('university_id', $scholarship->university_id ?? '') == $uni->id ? 'selected' : '' }}>
                    {{ $uni->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12 md:col-span-6 {{ old('university_id', $scholarship->university_id ?? '') ? 'hidden' : '' }}"
        id="provider_field">
        <label for="provider_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Provider Name
            <span class="text-red-500">*</span></label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="provider_name" name="provider_name"
            value="{{ old('provider_name', $scholarship->provider_name ?? '') }}" placeholder="e.g. British Council">
    </div>

    <div class="col-span-12 md:col-span-6 {{ old('university_id', $scholarship->university_id ?? '') ? 'hidden' : '' }}"
        id="ar_provider_field">
        <label for="ar_provider_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Provider Name (Arabic)</label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="ar_provider_name" name="ar_provider_name"
            value="{{ old('ar_provider_name', $scholarship->ar_provider_name ?? '') }}" placeholder="مثال: المجلس الثقافي البريطاني">
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Scholarship Name <span
                class="text-red-500">*</span></label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="name" name="name" value="{{ old('name', $scholarship->name ?? '') }}"
            placeholder="e.g. Excellence Scholarship" required>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="ar_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Scholarship Name (Arabic)</label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="ar_name" name="ar_name" value="{{ old('ar_name', $scholarship->ar_name ?? '') }}"
            placeholder="اسم المنحة">
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="deadline_date"
            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Deadline</label>
        <input type="date"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="deadline_date" name="deadline_date"
            value="{{ old('deadline_date', isset($scholarship->deadline_date) ? $scholarship->deadline_date->format('Y-m-d') : '') }}">
    </div>

    <!-- Amount Logic -->
    <div class="col-span-12 md:col-span-6">
        <label for="amount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Amount Type
            <span class="text-red-500">*</span></label>
        <select name="amount_type" id="amount_type"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            required>
            <option value="variable" {{ old('amount_type', $scholarship->amount_type ?? 'variable') == 'variable' ? 'selected' : '' }}>Variable (Description)</option>
            <option value="fixed" {{ old('amount_type', $scholarship->amount_type ?? '') == 'fixed' ? 'selected' : '' }}>
                Fixed Amount</option>
            <option value="percentage" {{ old('amount_type', $scholarship->amount_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
        </select>
    </div>

    <div class="col-span-12 md:col-span-6 hidden" id="amount_value_group">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Value & Currency</label>
        <div class="flex gap-2">
            <input type="number" step="0.01" name="amount_value"
                class="form-control w-2/3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                value="{{ old('amount_value', $scholarship->amount_value ?? '') }}" placeholder="Value">
            <input type="text" name="currency"
                class="form-control w-1/3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                value="{{ old('currency', $scholarship->currency ?? 'USD') }}" placeholder="USD" maxlength="3">
        </div>
    </div>

    <div class="col-span-12">
        <label for="summary" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Summary</label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="summary" name="summary" value="{{ old('summary', $scholarship->summary ?? '') }}"
            placeholder="Short description for cards (max 255 chars)">
    </div>

    <div class="col-span-12">
        <label for="ar_summary" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Summary (Arabic)</label>
        <input type="text"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="ar_summary" name="ar_summary" value="{{ old('ar_summary', $scholarship->ar_summary ?? '') }}"
            placeholder="وصف مختصر">
    </div>

    <div class="col-span-12">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Full
            Description</label>
        <textarea name="description" id="description" rows="4"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('description', $scholarship->description ?? '') }}</textarea>
    </div>

    <div class="col-span-12">
        <label for="ar_description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Full Description (Arabic)</label>
        <textarea name="ar_description" id="ar_description" rows="4"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('ar_description', $scholarship->ar_description ?? '') }}</textarea>
    </div>

    <div class="col-span-12">
        <label for="eligibility_text"
            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Eligibility Criteria</label>
        <textarea name="eligibility_text" id="eligibility_text" rows="4"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('eligibility_text', $scholarship->eligibility_text ?? '') }}</textarea>
    </div>

    <div class="col-span-12">
        <label for="ar_eligibility_text"
            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Eligibility Criteria (Arabic)</label>
        <textarea name="ar_eligibility_text" id="ar_eligibility_text" rows="4"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">{{ old('ar_eligibility_text', $scholarship->ar_eligibility_text ?? '') }}</textarea>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="apply_link" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Official Apply
            Link</label>
        <input type="url"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
            id="apply_link" name="apply_link" value="{{ old('apply_link', $scholarship->apply_link ?? '') }}"
            placeholder="https://...">
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status</label>
        <select name="is_active" id="is_active"
            class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="1" {{ old('is_active', $scholarship->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $scholarship->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive
            </option>
        </select>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uniSelect = document.getElementById('university_id');
        const providerField = document.getElementById('provider_field');
        const arProviderField = document.getElementById('ar_provider_field');

        uniSelect.addEventListener('change', function () {
            if (this.value) {
                providerField.classList.add('hidden');
                arProviderField.classList.add('hidden');
            } else {
                providerField.classList.remove('hidden');
                arProviderField.classList.remove('hidden');
            }
        });

        const amountType = document.getElementById('amount_type');
        const amountValueGroup = document.getElementById('amount_value_group');

        function toggleAmount() {
            if (amountType.value === 'fixed' || amountType.value === 'percentage') {
                amountValueGroup.classList.remove('hidden');
            } else {
                amountValueGroup.classList.add('hidden');
            }
        }
        amountType.addEventListener('change', toggleAmount);
        toggleAmount(); // init
    });
</script>
