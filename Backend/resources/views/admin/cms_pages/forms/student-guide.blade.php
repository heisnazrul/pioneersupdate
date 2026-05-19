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

{{-- Categories Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Guide Categories</div>
    </div>
    <div class="box-body">
        <div id="guide-categories">
            @foreach($content['categories'] ?? [] as $index => $category)
                <div class="border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 md:col-span-4">
                            <label class="{{ $labelClass }}">Title</label>
                            <input type="text" class="{{ $inputClass }}" name="content[categories][{{$index}}][title]"
                                value="{{ $category['title'] }}">
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="{{ $labelClass }}">Icon Class (FontAwesome)</label>
                            <input type="text" class="{{ $inputClass }}" name="content[categories][{{$index}}][icon]"
                                value="{{ $category['icon'] }}">
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="{{ $labelClass }}">Color Class</label>
                            <input type="text" class="{{ $inputClass }}" name="content[categories][{{$index}}][color]"
                                value="{{ $category['color'] }}">
                        </div>
                        <div class="col-span-12">
                            <label class="{{ $labelClass }}">Description</label>
                            <input type="text" class="{{ $inputClass }}" name="content[categories][{{$index}}][description]"
                                value="{{ $category['description'] }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <small class="text-gray-500">Note: Currently, adding new categories requires developer intervention for
            icons.</small>
    </div>
</div>

{{-- Trust Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Trust Section</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[trust_section][title]"
                    value="{{ $content['trust_section']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">CTA Button Text</label>
                <input type="text" class="{{ $inputClass }}" name="content[trust_section][cta_text]"
                    value="{{ $content['trust_section']['cta_text'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-3">
                <label class="{{ $labelClass }}">CTA Button Link</label>
                <input type="text" class="{{ $inputClass }}" name="content[trust_section][cta_link]"
                    value="{{ $content['trust_section']['cta_link'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[trust_section][description]"
                    rows="3">{{ $content['trust_section']['description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Tools & Resources Section --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Tools & Resources</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6 mb-4">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Title</label>
                <input type="text" class="{{ $inputClass }}" name="content[tools_resources][title]"
                    value="{{ $content['tools_resources']['title'] ?? '' }}">
            </div>
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Section Subtitle</label>
                <input type="text" class="{{ $inputClass }}" name="content[tools_resources][subtitle]"
                    value="{{ $content['tools_resources']['subtitle'] ?? '' }}">
            </div>
            <div class="col-span-12">
                <label class="{{ $labelClass }}">Description</label>
                <textarea class="{{ $inputClass }}" name="content[tools_resources][description]"
                    rows="2">{{ $content['tools_resources']['description'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50 text-blue-700 rounded-md border border-blue-200">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Note:</strong> The downloadable resources in this section are now automatically populated from the
            <a href="{{ route('admin.destination-guides.index') }}" class="underline font-bold">Destination Guides</a>
            module.
            Only the section title and description can be edited here.
        </div>
    </div>
</div>

{{-- Destination Guides Section (Powered by Blogs) --}}
<div class="box border border-gray-200 dark:border-white/10 mb-4">
    <div class="box-header bg-gray-50 dark:bg-black/20">
        <div class="box-title">Featured Guides (From Blogs)</div>
    </div>
    <div class="box-body">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-6">
                <label class="{{ $labelClass }}">Select Blog Category to Display</label>
                <select class="{{ $inputClass }}" name="content[featured_guides_category_slug]">
                    <option value="">-- None (Hidden) --</option>
                    @if(isset($blogCategories))
                        @foreach($blogCategories as $category)
                            <option value="{{ $category->slug }}" {{ ($content['featured_guides_category_slug'] ?? '') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <small class="text-gray-500 mt-1 block">
                    Select a blog category to populate the "Must-Read Guides" section. The most recent blogs from this
                    category will be shown.
                </small>
            </div>
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="box border border-gray-200 dark:border-white/10 mb-4">
        <div class="box-header bg-gray-50 dark:bg-black/20">
            <div class="box-title">Frequently Asked Questions</div>
        </div>
        <div class="box-body">
            <div class="grid grid-cols-12 gap-6 mb-4">
                <div class="col-span-12 md:col-span-6">
                    <label class="{{ $labelClass }}">Section Title</label>
                    <input type="text" class="{{ $inputClass }}" name="content[faq][title]"
                        value="{{ $content['faq']['title'] ?? '' }}">
                </div>
                <div class="col-span-12 md:col-span-6">
                    <label class="{{ $labelClass }}">Section Subtitle</label>
                    <input type="text" class="{{ $inputClass }}" name="content[faq][subtitle]"
                        value="{{ $content['faq']['subtitle'] ?? '' }}">
                </div>
                <div class="col-span-12">
                    <label class="{{ $labelClass }}">Description</label>
                    <textarea class="{{ $inputClass }}" name="content[faq][description]"
                        rows="2">{{ $content['faq']['description'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6 mb-4 mt-6 border-t border-gray-200 dark:border-white/10 pt-4">
                <div class="col-span-12">
                    <h6 class="font-bold mb-3 text-gray-800 dark:text-white">Sidebar CTA Card</h6>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <label class="{{ $labelClass }}">Card Title</label>
                    <input type="text" class="{{ $inputClass }}" name="content[faq][cta][title]"
                        value="{{ $content['faq']['cta']['title'] ?? '' }}">
                </div>
                <div class="col-span-12 md:col-span-6">
                    <label class="{{ $labelClass }}">Button Text</label>
                    <input type="text" class="{{ $inputClass }}" name="content[faq][cta][btn_text]"
                        value="{{ $content['faq']['cta']['btn_text'] ?? '' }}">
                </div>
                <div class="col-span-12 md:col-span-6">
                    <label class="{{ $labelClass }}">Button Link</label>
                    <input type="text" class="{{ $inputClass }}" name="content[faq][cta][btn_link]"
                        value="{{ $content['faq']['cta']['btn_link'] ?? '' }}">
                </div>
                <div class="col-span-12">
                    <label class="{{ $labelClass }}">Card Description</label>
                    <textarea class="{{ $inputClass }}" name="content[faq][cta][description]"
                        rows="2">{{ $content['faq']['cta']['description'] ?? '' }}</textarea>
                </div>
            </div>

            <h6 class="font-bold mb-3 text-gray-800 dark:text-white mt-6">Questions & Answers</h6>
            <div id="faq-items-wrapper">
                @php
                    $faqItems = $content['faq']['items'] ?? [];
                    // Ensure at least one empty item if none exist, so the loop runs or we have a base
                    if (empty($faqItems))
                        $faqItems = []; 
                @endphp

                @foreach($faqItems as $index => $item)
                    <div class="faq-item border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative group">
                        <button type="button"
                            class="absolute top-2 right-2 text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="this.closest('.faq-item').remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12">
                                <label class="{{ $labelClass }}">Question</label>
                                <input type="text" class="{{ $inputClass }}"
                                    name="content[faq][items][{{$index}}][question]" value="{{ $item['question'] ?? '' }}">
                            </div>
                            <div class="col-span-12">
                                <label class="{{ $labelClass }}">Answer</label>
                                <textarea class="{{ $inputClass }}" name="content[faq][items][{{$index}}][answer]"
                                    rows="3">{{ $item['answer'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-faq-btn"
                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                <i class="fas fa-plus mr-1"></i> Add New Question
            </button>

            {{-- Template for new items --}}
            <template id="faq-item-template">
                <div class="faq-item border border-gray-200 dark:border-white/10 p-4 mb-3 rounded-sm relative group">
                    <button type="button"
                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition-opacity"
                        onclick="this.closest('.faq-item').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label class="{{ $labelClass }}">Question</label>
                            <input type="text" class="{{ $inputClass }}" name="content[faq][items][INDEX][question]">
                        </div>
                        <div class="col-span-12">
                            <label class="{{ $labelClass }}">Answer</label>
                            <textarea class="{{ $inputClass }}" name="content[faq][items][INDEX][answer]"
                                rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </template>

            <script>
                document.getElementById('add-faq-btn').addEventListener('click', function () {
                    const wrapper = document.getElementById('faq-items-wrapper');
                    const template = document.getElementById('faq-item-template');
                    const index = wrapper.querySelectorAll('.faq-item').length; // Simple index based on count. 
                    // Note: If deleting items, index collision might happen if we just count. 
                    // Better to use Date.now() for unique index if array keys matter significantly, 
                    // but Laravel's name="...[]" or index based usually fine if we just submit the form.
                    // However, Laravel's validation or processing might expect sequential keys if we explicitly use [0], [1].
                    // Let's use a timestamp to avoid collision, or just re-index on submit. 
                    // For simplicity here, we'll use a unique ID logic.

                    const uniqueIndex = Date.now();

                    let html = template.innerHTML.replace(/INDEX/g, uniqueIndex);

                    // Create a temp container to turn string into DOM
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    wrapper.appendChild(temp.firstElementChild);
                });
            </script>
        </div>
    </div>