@php
    $existingImages = collect(old('existing_images', $detail?->images ?? []))
        ->filter()
        ->values()
        ->all();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Summer Camp</label>
        <select name="camp_id" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="">Select summer camp</option>
            @foreach($camps as $campOption)
                <option value="{{ $campOption->id }}" {{ (int) old('camp_id', $detail->camp_id ?? 0) === $campOption->id ? 'selected' : '' }}>
                    {{ $campOption->name }} ({{ $campOption->branch->school->name ?? 'School' }} / {{ $campOption->branch->slug ?? 'Branch' }})
                </option>
            @endforeach
        </select>
        @error('camp_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Images</label>
        <input type="file" name="images[]" multiple accept="image/*" class="mt-1 w-full border rounded px-3 py-2">
        <p class="mt-1 text-xs text-gray-500">You can upload multiple images.</p>
        @error('images')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('images.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

@if(!empty($detail?->images))
    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Existing Images</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($detail->images as $imagePath)
                <label class="border rounded p-2 bg-white">
                    <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}" alt="Summer camp image" class="h-24 w-full object-cover rounded border">
                    <div class="mt-2 flex items-center gap-2 text-xs text-gray-700">
                        <input type="checkbox" name="existing_images[]" value="{{ $imagePath }}" {{ in_array($imagePath, $existingImages, true) ? 'checked' : '' }}>
                        <span>Keep image</span>
                    </div>
                </label>
            @endforeach
        </div>
        <p class="mt-2 text-xs text-gray-500">Uncheck an image to remove it on update.</p>
    </div>
@endif

<div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Overview</label>
        <textarea name="overview" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('overview', $detail->overview ?? '') }}</textarea>
        @error('overview')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Overview (AR)</label>
        <textarea name="ar_overview" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_overview', $detail->ar_overview ?? '') }}</textarea>
        @error('ar_overview')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Academics</label>
        <textarea name="academics" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('academics', $detail->academics ?? '') }}</textarea>
        @error('academics')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Academics (AR)</label>
        <textarea name="ar_academics" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_academics', $detail->ar_academics ?? '') }}</textarea>
        @error('ar_academics')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Activities</label>
        <textarea name="activities" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('activities', $detail->activities ?? '') }}</textarea>
        @error('activities')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Activities (AR)</label>
        <textarea name="ar_activities" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_activities', $detail->ar_activities ?? '') }}</textarea>
        @error('ar_activities')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Accommodation</label>
        <textarea name="accommodation" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('accommodation', $detail->accommodation ?? '') }}</textarea>
        @error('accommodation')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Accommodation (AR)</label>
        <textarea name="ar_accommodation" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_accommodation', $detail->ar_accommodation ?? '') }}</textarea>
        @error('ar_accommodation')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Safeguarding</label>
        <textarea name="safeguarding" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('safeguarding', $detail->safeguarding ?? '') }}</textarea>
        @error('safeguarding')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Safeguarding (AR)</label>
        <textarea name="ar_safeguarding" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_safeguarding', $detail->ar_safeguarding ?? '') }}</textarea>
        @error('ar_safeguarding')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
