@php
    $content = $cmsPage->content ?? [];
    $inputClass = "mt-1 block w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:border-primary focus:ring focus:ring-primary dark:bg-black/20 dark:border-white/10 dark:text-white";
    $labelClass = "block text-sm font-medium text-gray-700 dark:text-white mb-2";
@endphp

<!-- Hero Section -->
<div class="box">
    <div class="box-header">
        <div class="box-title">Hero Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6">
                <label for="hero_badge" class="{{ $labelClass }}">Badge Text</label>
                <input type="text" class="{{ $inputClass }}" id="hero_badge" name="content[hero][badge]"
                    value="{{ old('content.hero.badge', $content['hero']['badge'] ?? '') }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label for="hero_title" class="{{ $labelClass }}">Hero Title</label>
                <input type="text" class="{{ $inputClass }}" id="hero_title" name="content[hero][title]"
                    value="{{ old('content.hero.title', $content['hero']['title'] ?? '') }}">
            </div>
            <div class="col-span-12">
                <label for="hero_description" class="{{ $labelClass }}">Hero Description</label>
                <textarea class="{{ $inputClass }}" id="hero_description" name="content[hero][description]"
                    rows="3">{{ old('content.hero.description', $content['hero']['description'] ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<!-- General Contact Info -->
<div class="box mt-4">
    <div class="box-header">
        <div class="box-title">Contact Information</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6">
                <label for="contact_email" class="{{ $labelClass }}">Email</label>
                <input type="email" class="{{ $inputClass }}" id="contact_email" name="content[contact_info][email]"
                    value="{{ old('content.contact_info.email', $content['contact_info']['email'] ?? '') }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label for="contact_phone" class="{{ $labelClass }}">Phone</label>
                <input type="text" class="{{ $inputClass }}" id="contact_phone" name="content[contact_info][phone]"
                    value="{{ old('content.contact_info.phone', $content['contact_info']['phone'] ?? '') }}">
            </div>
            <div class="col-span-12">
                <label for="contact_desc" class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" id="contact_desc" name="content[contact_info][description]"
                    rows="2">{{ old('content.contact_info.description', $content['contact_info']['description'] ?? '') }}</textarea>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="font-bold mb-2 text-gray-800 dark:text-white">Social Links</h6>
            @foreach($content['contact_info']['social_links'] ?? [] as $index => $link)
                <div class="grid grid-cols-12 gap-4 mb-2">
                    <div class="col-span-12 md:col-span-3">
                        <input type="text" class="{{ $inputClass }} bg-gray-50" readonly
                            name="content[contact_info][social_links][{{ $index }}][platform]"
                            value="{{ $link['platform'] }}">
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input type="text" class="{{ $inputClass }}"
                            name="content[contact_info][social_links][{{ $index }}][url]"
                            value="{{ old('content.contact_info.social_links.' . $index . '.url', $link['url']) }}"
                            placeholder="URL">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Offices Section Reference -->
<div class="box mt-4">
    <div class="box-header">
        <div class="box-title">Global Offices</div>
    </div>
    <div class="box-body">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 dark:bg-blue-900/30 dark:border-blue-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-200">
                        Global Offices are no longer managed here. Please use the dedicated
                        <a href="{{ route('admin.offices.index') }}" class="font-medium underline hover:text-blue-600">
                            Office Management
                        </a> page to add, edit, or remove offices.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h6 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Office
                Display Settings</h6>
            <div class="flex items-center">
                <input type="hidden" name="content[offices_display][show_map]" value="0">
                <input type="checkbox" id="show_map" name="content[offices_display][show_map]" value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    {{ ($content['offices_display']['show_map'] ?? '1') == '1' ? 'checked' : '' }}>
                <label for="show_map" class="ml-2 block text-sm text-gray-900 dark:text-white">
                    Show Global Map on Contact Page
                </label>
            </div>
        </div>
    </div>
</div>