<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 md:col-span-6">
        <label for="university_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">University <span class="text-red-500">*</span></label>
        <select name="university_id" id="university_id" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5" required>
            <option value="">Select University</option>
            @foreach($universities as $uni)
                <option value="{{ $uni->id }}" {{ old('university_id', $campus->university_id ?? '') == $uni->id ? 'selected' : '' }}>
                    {{ $uni->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Campus Name <span class="text-red-500">*</span></label>
        <input type="text" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5" 
               id="name" name="name" value="{{ old('name', $campus->name ?? '') }}" placeholder="e.g. Main Campus" required>
    </div>

    <div class="col-span-12 md:col-span-6">
        <div class="flex items-center gap-2 mb-2">
            <input type="hidden" name="is_online" value="0">
            <input type="checkbox" name="is_online" id="is_online" value="1" 
                   class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                   {{ old('is_online', $campus->is_online ?? 0) ? 'checked' : '' }}
                   onchange="toggleCityField(this)">
            <label for="is_online" class="text-sm font-medium text-gray-700 dark:text-gray-200">This is an Online Campus</label>
        </div>
        <p class="text-xs text-gray-500 mb-4">If checked, City/Address are optional.</p>

        <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">City <span class="text-red-500" id="city_required">*</span></label>
        <select name="city_id" id="city_id" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="">Select City</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ old('city_id', $campus->city_id ?? '') == $city->id ? 'selected' : '' }}>
                    {{ $city->name }} ({{ $city->country->name ?? 'N/A' }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Address</label>
        <textarea name="address" id="address" rows="3" 
                  class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                  placeholder="Full street address">{{ old('address', $campus->address ?? '') }}</textarea>
    </div>

    <div class="col-span-12 md:col-span-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                 <label for="lat" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Latitude</label>
                 <input type="number" step="any" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5" 
                   id="lat" name="lat" value="{{ old('lat', $campus->lat ?? '') }}">
            </div>
            <div>
                 <label for="lng" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Longitude</label>
                 <input type="number" step="any" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5" 
                   id="lng" name="lng" value="{{ old('lng', $campus->lng ?? '') }}">
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-6">
        <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status</label>
        <select name="is_active" id="is_active" class="form-control w-full bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5">
            <option value="1" {{ old('is_active', $campus->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $campus->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>

<script>
    function toggleCityField(checkbox) {
        const citySelect = document.getElementById('city_id');
        const cityReq = document.getElementById('city_required');
        
        if (checkbox.checked) {
            cityReq.style.display = 'none';
            // Optional: clear validation state visually
        } else {
            cityReq.style.display = 'inline';
        }
    }
    
    // Initial run
    document.addEventListener('DOMContentLoaded', function() {
        toggleCityField(document.getElementById('is_online'));
    });
</script>
