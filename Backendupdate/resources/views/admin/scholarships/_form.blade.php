@php($tagValues = collect(old('tags', $scholarship->tags ?? []))->pad(5, '')->all())
@php($nationalityValues = collect(old('eligible_nationalities', $scholarship->eligible_nationalities ?? []))->pad(5, '')->all())

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">University</label>
                <select name="university_id" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">External / Provider-based</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->id }}" {{ (string) old('university_id', $scholarship->university_id ?? '') === (string) $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Provider Name</label>
                <input type="text" name="provider_name" value="{{ old('provider_name', $scholarship->provider_name ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Scholarship Name *</label>
                <input type="text" name="name" value="{{ old('name', $scholarship->name ?? '') }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Name</label>
                <input type="text" name="ar_name" value="{{ old('ar_name', $scholarship->ar_name ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Amount Type *</label>
                <select name="amount_type" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    @foreach(['fixed' => 'Fixed', 'percentage' => 'Percentage', 'variable' => 'Variable'] as $value => $label)
                        <option value="{{ $value }}" {{ old('amount_type', $scholarship->amount_type ?? 'variable') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Amount Value</label>
                <input type="number" step="0.01" name="amount_value" value="{{ old('amount_value', $scholarship->amount_value ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $scholarship->currency ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Min Amount</label>
                <input type="number" step="0.01" name="min_amount" value="{{ old('min_amount', $scholarship->min_amount ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Max Amount</label>
                <input type="number" step="0.01" name="max_amount" value="{{ old('max_amount', $scholarship->max_amount ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Deadline Date</label>
                <input type="date" name="deadline_date" value="{{ old('deadline_date', isset($scholarship) && $scholarship->deadline_date ? $scholarship->deadline_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Summary</label>
                <input type="text" name="summary" value="{{ old('summary', $scholarship->summary ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Summary</label>
                <input type="text" name="ar_summary" value="{{ old('ar_summary', $scholarship->ar_summary ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('description', $scholarship->description ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Description</label>
                <textarea name="ar_description" rows="5" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_description', $scholarship->ar_description ?? '') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Eligibility Text</label>
                <textarea name="eligibility_text" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('eligibility_text', $scholarship->eligibility_text ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Arabic Eligibility Text</label>
                <textarea name="ar_eligibility_text" rows="4" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">{{ old('ar_eligibility_text', $scholarship->ar_eligibility_text ?? '') }}</textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Apply Link</label>
            <input type="url" name="apply_link" value="{{ old('apply_link', $scholarship->apply_link ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <label class="block text-sm font-medium">Tags</label>
                @foreach($tagValues as $value)
                    <input type="text" name="tags[]" value="{{ $value }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" placeholder="Tag">
                @endforeach
            </div>
            <div class="space-y-3">
                <label class="block text-sm font-medium">Eligible Nationalities</label>
                @foreach($nationalityValues as $value)
                    <input type="text" name="eligible_nationalities[]" value="{{ $value }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" placeholder="Nationality">
                @endforeach
            </div>
        </div>
    </div>

    <label class="flex items-center gap-3 text-sm font-medium">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $scholarship->is_active ?? true) ? 'checked' : '' }}>
        Active
    </label>

    <div class="flex gap-3">
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
        <a href="{{ route('admin.scholarships.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
    </div>
</div>
