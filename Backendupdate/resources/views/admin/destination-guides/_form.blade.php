@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium mb-1">Destination *</label>
            <select name="destination_id" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <option value="">Select destination</option>
                @foreach($destinations as $destination)
                    <option value="{{ $destination->id }}" {{ (string) old('destination_id', $destinationGuide->destination_id ?? '') === (string) $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Year</label>
            <input type="number" name="year" value="{{ old('year', $destinationGuide->year ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $destinationGuide->title ?? '') }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Arabic Title</label>
            <input type="text" name="ar_title" value="{{ old('ar_title', $destinationGuide->ar_title ?? '') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700" dir="rtl">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ isset($destinationGuide) ? 'Replace PDF' : 'PDF File *' }}</label>
        <input type="file" name="file" accept="application/pdf" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        @if(isset($destinationGuide) && $destinationGuide->file_path)
            <a href="{{ Storage::url($destinationGuide->file_path) }}" target="_blank" class="inline-block mt-2 text-sm text-primary-600">Open current PDF</a>
        @endif
    </div>
    <label class="flex items-center gap-3 text-sm font-medium">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $destinationGuide->is_active ?? true) ? 'checked' : '' }}>
        Active
    </label>
</div>

<div class="flex gap-3">
    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">{{ $submitLabel }}</button>
    <a href="{{ route('admin.destination-guides.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
</div>
