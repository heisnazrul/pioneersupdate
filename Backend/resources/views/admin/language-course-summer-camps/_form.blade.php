@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" value="{{ old('name', $camp->name ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Name</label>
        <input type="text" name="ar_name" value="{{ old('ar_name', $camp->ar_name ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Branch</label>
        <select name="branch_id" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="">Select branch</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" {{ (int)old('branch_id', $camp->branch_id ?? 0) === $b->id ? 'selected' : '' }}>
                    {{ $b->school->name ?? 'School' }} / {{ $b->slug }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Course Type</label>
        <select name="course_type_id" class="mt-1 w-full border rounded px-3 py-2" required>
            @foreach($types as $t)
                <option value="{{ $t->id }}" {{ (int)old('course_type_id', $camp->course_type_id ?? 0) === $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tag</label>
        <select name="tag_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">None</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ (int)old('tag_id', $camp->tag_id ?? 0) === $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Fee Type</label>
        @php $ft = old('fee_type', $camp->fee_type ?? 'weekly'); @endphp
        <select name="fee_type" class="mt-1 w-full border rounded px-3 py-2">
            <option value="weekly" {{ $ft==='weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="flat" {{ $ft==='flat' ? 'selected' : '' }}>Flat</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Fee Amount</label>
        <input type="number" step="0.01" min="0" name="fee_amount" value="{{ old('fee_amount', $camp->fee_amount ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Registration Fee</label>
        <input type="number" step="0.01" min="0" name="registration_fee" value="{{ old('registration_fee', $camp->registration_fee ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Lessons / week</label>
        <input type="number" min="0" name="lessons_per_week" value="{{ old('lessons_per_week', $camp->lessons_per_week ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Age Range</label>
        <input type="text" name="age_range" value="{{ old('age_range', $camp->age_range ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="text" name="start_date" value="{{ old('start_date', $camp->start_date ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Payment Deadline</label>
        <input type="date" name="payment_deadline" value="{{ old('payment_deadline', optional($camp->payment_deadline ?? null)->format('Y-m-d')) }}" class="mt-1 w-full border rounded px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        @php $status = old('status', $camp->status ?? 'published'); @endphp
        <select name="status" class="mt-1 w-full border rounded px-3 py-2">
            <option value="draft" {{ $status==='draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ $status==='published' ? 'selected' : '' }}>Published</option>
            <option value="suspended" {{ $status==='suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
    </div>
    <div class="flex items-center gap-3 mt-6">
        <input type="hidden" name="visible" value="0">
        <input type="checkbox" name="visible" value="1" {{ old('visible', $camp->visible ?? 1) ? 'checked' : '' }}>
        <span class="text-sm text-gray-700">Visible</span>
    </div>
</div>

<div class="mt-3">
    <label class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $camp->description ?? '') }}</textarea>
</div>
<div class="mt-3">
    <label class="block text-sm font-medium text-gray-700">Arabic Description</label>
    <textarea name="ar_description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_description', $camp->ar_description ?? '') }}</textarea>
</div>

<div class="mt-3">
    <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
    <input type="file" name="thumbnail" accept="image/*" class="mt-1 w-full border rounded px-3 py-2">
    @if(!empty($camp?->thumbnail))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $camp->thumbnail) }}" alt="Thumbnail" class="h-16 w-24 object-cover rounded border">
        </div>
    @endif
</div>

@if(isset($detail))
<div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Overview</label>
        <textarea name="overview" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('overview', $detail->overview ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Overview</label>
        <textarea name="ar_overview" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_overview', $detail->ar_overview ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Academics</label>
        <textarea name="academics" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('academics', $detail->academics ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Academics</label>
        <textarea name="ar_academics" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_academics', $detail->ar_academics ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Activities</label>
        <textarea name="activities" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('activities', $detail->activities ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Activities</label>
        <textarea name="ar_activities" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_activities', $detail->ar_activities ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Accommodation</label>
        <textarea name="accommodation" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('accommodation', $detail->accommodation ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Accommodation</label>
        <textarea name="ar_accommodation" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_accommodation', $detail->ar_accommodation ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Safeguarding</label>
        <textarea name="safeguarding" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('safeguarding', $detail->safeguarding ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Arabic Safeguarding</label>
        <textarea name="ar_safeguarding" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('ar_safeguarding', $detail->ar_safeguarding ?? '') }}</textarea>
    </div>
</div>
@endif
