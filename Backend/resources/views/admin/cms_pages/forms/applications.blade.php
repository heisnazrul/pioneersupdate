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
            <div class="col-span-12 md:col-span-8">
                <label class="{{ $labelClass }}">Hero Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][title]"
                    value="{{ $content['hero']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Hero Description</label>
                <textarea class="{{ $inputClass }}" name="content[hero][description]"
                    rows="3">{{ $content['hero']['description'] ?? '' }}</textarea>
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button 1 Text (Primary)</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][btn1_text]"
                    value="{{ $content['hero']['btn1_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button 1 Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][btn1_link]"
                    value="{{ $content['hero']['btn1_link'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button 2 Text (Outline)</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][btn2_text]"
                    value="{{ $content['hero']['btn2_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button 2 Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[hero][btn2_link]"
                    value="{{ $content['hero']['btn2_link'] ?? '' }}">
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
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Subtitle</label>
                <input type="text" class="{{ $inputClass }}" name="content[services][subtitle]"
                    value="{{ $content['services']['subtitle'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-8">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[services][title]"
                    value="{{ $content['services']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[services][description]"
                    rows="2">{{ $content['services']['description'] ?? '' }}</textarea>
            </div>
        </div>

        <hr class="my-4 border-gray-200 dark:border-white/10">

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Service Cards</h6>
        <div id="services-wrapper">
            @php
                $services = $content['services']['items'] ?? [];
            @endphp
            @foreach($services as $index => $item)
                <div class="service-item border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative group">
                    <button type="button"
                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                        onclick="this.closest('.service-item').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-6">
                            <label class="{{ $labelClass }}">Title</label>
                            <input type="text" class="{{ $inputClass }}" name="content[services][items][{{$index}}][title]"
                                value="{{ $item['title'] ?? '' }}">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="{{ $labelClass }}">Icon (FontAwesome)</label>
                            <input type="text" class="{{ $inputClass }}" name="content[services][items][{{$index}}][icon]"
                                value="{{ $item['icon'] ?? '' }}">
                        </div>
                        <div class="col-span-12">
                            <label class="{{ $labelClass }}">Description</label>
                            <textarea class="{{ $inputClass }}" name="content[services][items][{{$index}}][description]"
                                rows="2">{{ $item['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-service-btn"
            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
            <i class="fas fa-plus mr-1"></i> Add Service
        </button>
    </div>
</div>

{{-- Process Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Process Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12 md:col-span-4">
                <label class="{{ $labelClass }}">Subtitle</label>
                <input type="text" class="{{ $inputClass }}" name="content[process][subtitle]"
                    value="{{ $content['process']['subtitle'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-8">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[process][title]"
                    value="{{ $content['process']['title'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[process][description]"
                    rows="2">{{ $content['process']['description'] ?? '' }}</textarea>
            </div>
        </div>

        <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Process Steps</h6>
        <div id="steps-wrapper">
            @php
                $steps = $content['process']['steps'] ?? [];
            @endphp
            @foreach($steps as $index => $item)
                <div class="step-item border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative group">
                    <button type="button"
                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                        onclick="this.closest('.step-item').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-2">
                            <label class="{{ $labelClass }}">Number</label>
                            <input type="text" class="{{ $inputClass }}" name="content[process][steps][{{$index}}][number]"
                                value="{{ $item['number'] ?? '' }}">
                        </div>
                        <div class="col-span-12 md:col-span-10">
                            <label class="{{ $labelClass }}">Title</label>
                            <input type="text" class="{{ $inputClass }}" name="content[process][steps][{{$index}}][title]"
                                value="{{ $item['title'] ?? '' }}">
                        </div>
                        <div class="col-span-12">
                            <label class="{{ $labelClass }}">Description</label>
                            <textarea class="{{ $inputClass }}" name="content[process][steps][{{$index}}][description]"
                                rows="2">{{ $item['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-step-btn"
            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
            <i class="fas fa-plus mr-1"></i> Add Step
        </button>

        <div class="grid grid-cols-12 gap-6 mt-8 border-t border-gray-200 pt-4">
            <div class="col-span-12">
                <h6 class="font-bold">Success Stat Box</h6>
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Stat Value</label>
                <input type="text" class="{{ $inputClass }}" name="content[process][stat][value]"
                    value="{{ $content['process']['stat']['value'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Stat Label</label>
                <input type="text" class="{{ $inputClass }}" name="content[process][stat][label]"
                    value="{{ $content['process']['stat']['label'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Stat Description</label>
                <textarea class="{{ $inputClass }}" name="content[process][stat][description]"
                    rows="2">{{ $content['process']['stat']['description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Main CTA Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Main CTA Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta][title]"
                    value="{{ $content['cta']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 ">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[cta][description]"
                    rows="2">{{ $content['cta']['description'] ?? '' }}</textarea>
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta][btn_text]"
                    value="{{ $content['cta']['btn_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[cta][btn_link]"
                    value="{{ $content['cta']['btn_link'] ?? '' }}">
            </div>
        </div>
    </div>
</div>

{{-- Templates for Dynamic Items --}}
<template id="service-item-template">
    <div class="service-item border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative group">
        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition-opacity"
            onclick="this.closest('.service-item').remove()">
            <i class="fas fa-trash"></i>
        </button>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[services][items][INDEX][title]">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Icon (FontAwesome)</label>
                <input type="text" class="{{ $inputClass }}" name="content[services][items][INDEX][icon]">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[services][items][INDEX][description]"
                    rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

<template id="step-item-template">
    <div class="step-item border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative group">
        <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition-opacity"
            onclick="this.closest('.step-item').remove()">
            <i class="fas fa-trash"></i>
        </button>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-2">
                <label class="{{ $labelClass }}">Number</label>
                <input type="text" class="{{ $inputClass }}" name="content[process][steps][INDEX][number]">
            </div>
            <div class="col-span-12 md:col-span-10">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[process][steps][INDEX][title]">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[process][steps][INDEX][description]"
                    rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    function setupRepeater(btnId, wrapperId, templateId, placeholder) {
        document.getElementById(btnId)?.addEventListener('click', function () {
            const wrapper = document.getElementById(wrapperId);
            const template = document.getElementById(templateId);
            const uniqueIndex = Date.now(); // Unique index

            let html = template.innerHTML.replace(/INDEX/g, uniqueIndex);
            const temp = document.createElement('div');
            temp.innerHTML = html;
            wrapper.appendChild(temp.firstElementChild);
        });
    }

    setupRepeater('add-service-btn', 'services-wrapper', 'service-item-template');
    setupRepeater('add-step-btn', 'steps-wrapper', 'step-item-template');
</script>