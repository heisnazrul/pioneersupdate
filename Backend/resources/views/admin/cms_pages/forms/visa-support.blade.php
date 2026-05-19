@php
    $content = $cmsPage->content ?? [];
    $inputClass = "mt-1 block w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:border-primary focus:ring focus:ring-primary dark:bg-black/20 dark:border-white/10 dark:text-white";
    $labelClass = "block text-sm font-medium text-gray-700 dark:text-white mb-2";
@endphp

{{-- Hero Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Hero Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Badge Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][badge]"
                    value="{{ $content['hero']['badge'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Hero Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][title]"
                    value="{{ $content['hero']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Hero Description</label>
                <textarea class="{{ $inputClass }}" name="content[hero][description]"
                    rows="3">{{ $content['hero']['description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Services Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Services Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[services_section][title]"
                    value="{{ $content['services_section']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Subtitle</label>
                <input type="text" class="{{ $inputClass }}" name="content[services_section][subtitle]"
                    value="{{ $content['services_section']['subtitle'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Section Description</label>
                <textarea class="{{ $inputClass }}" name="content[services_section][description]"
                    rows="2">{{ $content['services_section']['description'] ?? '' }}</textarea>
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Service Items</h6>
        @foreach($content['services_section']['items'] ?? [] as $index => $item)
            <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Title</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[services_section][items][{{$index}}][title]" value="{{ $item['title'] }}">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Icon</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[services_section][items][{{$index}}][icon]" value="{{ $item['icon'] }}">
                    </div>
                    <div class="col-span-12 md:col-span-12">
                        <label class="{{ $labelClass }}">Description</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[services_section][items][{{$index}}][description]"
                            value="{{ $item['description'] }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Process Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Process Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[process_section][title]"
                    value="{{ $content['process_section']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Section Description</label>
                <input type="text" class="{{ $inputClass }}" name="content[process_section][description]"
                    value="{{ $content['process_section']['description'] ?? '' }}">
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Steps</h6>
        @foreach($content['process_section']['steps'] ?? [] as $index => $step)
            <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 md:col-span-2">
                        <label class="{{ $labelClass }}">Number</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[process_section][steps][{{$index}}][number]" value="{{ $step['number'] }}">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Title</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[process_section][steps][{{$index}}][title]" value="{{ $step['title'] }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="{{ $labelClass }}">Description</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[process_section][steps][{{$index}}][description]"
                            value="{{ $step['description'] }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- CTA Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">CTA Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta_section][title]"
                    value="{{ $content['cta_section']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta_section][button_text]"
                    value="{{ $content['cta_section']['button_text'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[cta_section][description]"
                    rows="2">{{ $content['cta_section']['description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>