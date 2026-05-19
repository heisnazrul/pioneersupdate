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

{{-- Why Choose Us Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Why Choose Us?</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[why_choose_us][title]"
                    value="{{ $content['why_choose_us']['title'] ?? '' }}">
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Benefits</h6>
        @foreach($content['why_choose_us']['items'] ?? [] as $index => $item)
            <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Title</label>
                        <input type="text" class="{{ $inputClass }}" name="content[why_choose_us][items][{{$index}}][title]"
                            value="{{ $item['title'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-8">
                        <label class="{{ $labelClass }}">Description</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[why_choose_us][items][{{$index}}][description]"
                            value="{{ $item['description'] ?? '' }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Booking Process Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Booking Process</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[booking_process][title]"
                    value="{{ $content['booking_process']['title'] ?? '' }}">
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Steps</h6>
        @foreach($content['booking_process']['steps'] ?? [] as $index => $step)
            <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-2">
                        <label class="{{ $labelClass }}">Number</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[booking_process][steps][{{$index}}][number]" value="{{ $step['number'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <label class="{{ $labelClass }}">Title</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[booking_process][steps][{{$index}}][title]" value="{{ $step['title'] ?? '' }}">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="{{ $labelClass }}">Description</label>
                        <input type="text" class="{{ $inputClass }}"
                            name="content[booking_process][steps][{{$index}}][description]"
                            value="{{ $step['description'] ?? '' }}">
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