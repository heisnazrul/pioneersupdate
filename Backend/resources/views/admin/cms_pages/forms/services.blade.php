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
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Primary Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][button_text]"
                    value="{{ $content['hero']['button_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Primary Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][button_link]"
                    value="{{ $content['hero']['button_link'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Secondary Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][secondary_button_text]"
                    value="{{ $content['hero']['secondary_button_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Secondary Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][secondary_button_link]"
                    value="{{ $content['hero']['secondary_button_link'] ?? '' }}">
            </div>
        </div>
    </div>
</div>

{{-- What We Offer Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">What We Offer</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[what_we_offer][title]"
                    value="{{ $content['what_we_offer']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Description</label>
                <input type="text" class="{{ $inputClass }}" name="content[what_we_offer][description]"
                    value="{{ $content['what_we_offer']['description'] ?? '' }}">
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Offer Items</h6>
        @foreach($content['what_we_offer']['items'] ?? [] as $index => $item)
            <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Title</label>
                        <input type="text" class="{{ $inputClass }}" name="content[what_we_offer][items][{{$index}}][title]"
                            value="{{ $item['title'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Icon Class</label>
                        <input type="text" class="{{ $inputClass }}" name="content[what_we_offer][items][{{$index}}][icon]"
                            value="{{ $item['icon'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Description</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[what_we_offer][items][{{$index}}][description]"
                            value="{{ $item['description'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-12">
                        <label class="{{ $labelClass }}">Link</label>
                        <input type="text" class="{{ $inputClass }}" name="content[what_we_offer][items][{{$index}}][link]"
                            value="{{ $item['link'] ?? '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Our Process Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Our Process</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[our_process][title]"
                    value="{{ $content['our_process']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Description</label>
                <input type="text" class="{{ $inputClass }}" name="content[our_process][description]"
                    value="{{ $content['our_process']['description'] ?? '' }}">
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Process Steps</h6>
        @foreach($content['our_process']['steps'] ?? [] as $index => $step)
            <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-2">
                        <label class="{{ $labelClass }}">Number</label>
                        <input type="text" class="{{ $inputClass }}" name="content[our_process][steps][{{$index}}][number]"
                            value="{{ $step['number'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Title</label>
                        <input type="text" class="{{ $inputClass }}" name="content[our_process][steps][{{$index}}][title]"
                            value="{{ $step['title'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="{{ $labelClass }}">Description</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[our_process][steps][{{$index}}][description]"
                            value="{{ $step['description'] ?? '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Partner Section (New) --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Partner Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Badge</label>
                <input type="text" class="{{ $inputClass }}" name="content[partner_section][badge]"
                    value="{{ $content['partner_section']['badge'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[partner_section][title]"
                    value="{{ $content['partner_section']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[partner_section][description]"
                    rows="2">{{ $content['partner_section']['description'] ?? '' }}</textarea>
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Primary Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[partner_section][button_text]"
                    value="{{ $content['partner_section']['button_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Primary Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[partner_section][button_link]"
                    value="{{ $content['partner_section']['button_link'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Secondary Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[partner_section][secondary_button_text]"
                    value="{{ $content['partner_section']['secondary_button_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">Secondary Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[partner_section][secondary_button_link]"
                    value="{{ $content['partner_section']['secondary_button_link'] ?? '' }}">
            </div>
        </div>
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
                <input type="text" class="{{ $inputClass }}" name="content[cta][title]"
                    value="{{ $content['cta']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Description</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta][description]"
                    value="{{ $content['cta']['description'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta][button_text]"
                    value="{{ $content['cta']['button_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta][button_link]"
                    value="{{ $content['cta']['button_link'] ?? '' }}">
            </div>
        </div>
    </div>
</div>