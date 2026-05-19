@extends('admin.layouts.layout')

@php
    $branding = $branding ?? [];
    $app = $app ?? 'courseenglish';
    $isUniversity = $app === 'university';
    $routePrefix = $isUniversity ? 'admin.cms.university.branding' : 'admin.cms.course-english.branding';
    $header = $branding['header'] ?? [];
    $footer = $branding['footer'] ?? [];
    $mobile = $branding['mobile'] ?? [];

    $topNav = $header['top_nav'] ?? [];
    $mainNav = $header['main_nav'] ?? [];
    $currencies = $header['currencies'] ?? [];
    $languages = $header['languages'] ?? [];

    $columns = $footer['columns'] ?? [];
    $social = $footer['social'] ?? [];

    $navCount = max(count($topNav), 6);
    $mainCount = max(count($mainNav), 5);
    $currCount = max(count($currencies), 2);
    $langCount = max(count($languages), 2);
    $colCount = max(count($columns), 2);
    $colItemsCount = 4;
    $socialCount = max(count($social), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS</p>
            <h2 class="text-2xl font-bold">{{ $isUniversity ? 'University Branding' : 'CourseEnglish Branding' }} (Header / Footer / Mobile)</h2>
            <p class="text-sm text-gray-500 mt-1">Manage logos, navigation, actions, and footer content. Static only.</p>
        </div>
    </div>

    {{-- Header --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Header</h3>
            <span class="text-xs text-gray-500">Logos, nav, currency/language, buttons</span>
        </div>
        <form method="POST" action="{{ route($routePrefix.'.header') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo (EN)</label>
                    <input name="logo[main]" value="{{ old('logo.main', $header['logo']['main'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="/storage/branding/logo.png">
                    <input type="file" name="logo_main_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo (AR)</label>
                    <input name="logo[ar]" value="{{ old('logo.ar', $header['logo']['ar'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="/storage/branding/logo-ar.png">
                    <input type="file" name="logo_ar_file" class="mt-2 w-full text-sm">
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Top Navigation</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @for($i=0; $i < $navCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs text-gray-500">Label (EN)</label>
                            <input name="top_nav[{{ $i }}][label]" value="{{ old("top_nav.$i.label", $topNav[$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs text-gray-500">Label (AR)</label>
                            <input name="top_nav[{{ $i }}][ar_label]" value="{{ old("top_nav.$i.ar_label", $topNav[$i]['ar_label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs text-gray-500">URL</label>
                            <input name="top_nav[{{ $i }}][url]" value="{{ old("top_nav.$i.url", $topNav[$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Main Navigation</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @for($i=0; $i < $mainCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs text-gray-500">Label (EN)</label>
                            <input name="main_nav[{{ $i }}][label]" value="{{ old("main_nav.$i.label", $mainNav[$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs text-gray-500">Label (AR)</label>
                            <input name="main_nav[{{ $i }}][ar_label]" value="{{ old("main_nav.$i.ar_label", $mainNav[$i]['ar_label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs text-gray-500">URL</label>
                            <input name="main_nav[{{ $i }}][url]" value="{{ old("main_nav.$i.url", $mainNav[$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-100 rounded-lg p-3">
                    <h4 class="font-semibold mb-2">Currencies</h4>
                    @for($i=0; $i < $currCount; $i++)
                        <div class="grid grid-cols-4 gap-2 mb-2">
                            <input name="currencies[{{ $i }}][code]" value="{{ old("currencies.$i.code", $currencies[$i]['code'] ?? '') }}" class="col-span-1 border rounded px-2 py-1" placeholder="Code">
                            <input name="currencies[{{ $i }}][label]" value="{{ old("currencies.$i.label", $currencies[$i]['label'] ?? '') }}" class="col-span-1 border rounded px-2 py-1" placeholder="Label">
                            <input name="currencies[{{ $i }}][symbol]" value="{{ old("currencies.$i.symbol", $currencies[$i]['symbol'] ?? '') }}" class="col-span-1 border rounded px-2 py-1" placeholder="Symbol">
                            <input name="currencies[{{ $i }}][icon]" value="{{ old("currencies.$i.icon", $currencies[$i]['icon'] ?? '') }}" class="col-span-1 border rounded px-2 py-1" placeholder="Icon URL">
                            <input type="file" name="currencies[{{ $i }}][icon_file]" class="col-span-4 md:col-span-4 text-sm">
                        </div>
                    @endfor
                </div>
                <div class="border border-gray-100 rounded-lg p-3">
                    <h4 class="font-semibold mb-2">Languages</h4>
                    @for($i=0; $i < $langCount; $i++)
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <input name="languages[{{ $i }}][code]" value="{{ old("languages.$i.code", $languages[$i]['code'] ?? '') }}" class="border rounded px-2 py-1" placeholder="Code">
                            <input name="languages[{{ $i }}][label]" value="{{ old("languages.$i.label", $languages[$i]['label'] ?? '') }}" class="border rounded px-2 py-1" placeholder="Label">
                            <input name="languages[{{ $i }}][flag]" value="{{ old("languages.$i.flag", $languages[$i]['flag'] ?? '') }}" class="border rounded px-2 py-1" placeholder="Flag URL">
                            <input type="file" name="languages[{{ $i }}][flag_file]" class="col-span-3 text-sm">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Compare URL</label>
                    <input name="buttons[compare][url]" value="{{ old('buttons.compare.url', $header['buttons']['compare']['url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Wishlist URL</label>
                    <input name="buttons[wishlist][url]" value="{{ old('buttons.wishlist.url', $header['buttons']['wishlist']['url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account URL</label>
                    <input name="buttons[account][url]" value="{{ old('buttons.account.url', $header['buttons']['account']['url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Label (EN)</label>
                    <input name="buttons[account][label]" value="{{ old('buttons.account.label', $header['buttons']['account']['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Label (AR)</label>
                    <input name="buttons[account][ar_label]" value="{{ old('buttons.account.ar_label', $header['buttons']['account']['ar_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Header</button>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Footer</h3>
            <span class="text-xs text-gray-500">Subscribe, columns, social, copyright</span>
        </div>
        <form method="POST" action="{{ route($routePrefix.'.footer') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subscribe Heading</label>
                    <input name="subscribe[heading]" value="{{ old('subscribe.heading', $footer['subscribe']['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Placeholder</label>
                    <input name="subscribe[placeholder]" value="{{ old('subscribe.placeholder', $footer['subscribe']['placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Button Text</label>
                    <input name="subscribe[button_text]" value="{{ old('subscribe.button_text', $footer['subscribe']['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Columns</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i=0; $i < $colCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs text-gray-500">Title (EN)</label>
                            <input name="columns[{{ $i }}][title]" value="{{ old("columns.$i.title", $columns[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-2">
                            <label class="text-xs text-gray-500">Title (AR)</label>
                            <input name="columns[{{ $i }}][ar_title]" value="{{ old("columns.$i.ar_title", $columns[$i]['ar_title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-2" dir="rtl">
                            @for($j=0; $j < $colItemsCount; $j++)
                                <div class="border border-gray-100 rounded p-2 mb-2">
                                    <input name="columns[{{ $i }}][items][{{ $j }}][label]" value="{{ old("columns.$i.items.$j.label", $columns[$i]['items'][$j]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label (EN)">
                                    <input name="columns[{{ $i }}][items][{{ $j }}][ar_label]" value="{{ old("columns.$i.items.$j.ar_label", $columns[$i]['items'][$j]['ar_label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label (AR)" dir="rtl">
                                    <input name="columns[{{ $i }}][items][{{ $j }}][url]" value="{{ old("columns.$i.items.$j.url", $columns[$i]['items'][$j]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                                </div>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $footer['description'] ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                <textarea name="ar_description" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('ar_description', $footer['ar_description'] ?? '') }}</textarea>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Social</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @for($i=0; $i < $socialCount; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="social[{{ $i }}][platform]" value="{{ old("social.$i.platform", $social[$i]['platform'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Platform">
                            <input name="social[{{ $i }}][url]" value="{{ old("social.$i.url", $social[$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Brand (EN)</label>
                    <input name="brand" value="{{ old('brand', $footer['brand'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Brand (AR)</label>
                    <input name="ar_brand" value="{{ old('ar_brand', $footer['ar_brand'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Copyright (EN)</label>
                    <input name="copyright" value="{{ old('copyright', $footer['copyright'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Copyright (AR)</label>
                    <input name="ar_copyright" value="{{ old('ar_copyright', $footer['ar_copyright'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Footer Logo URL</label>
                    <input name="logo" value="{{ old('logo', $footer['logo'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="/logo.png or /storage/branding/footer-logo.png">
                    <input type="file" name="footer_logo_file" class="mt-2 w-full text-sm">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Footer</button>
            </div>
        </form>
    </div>

    {{-- Mobile --}}
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Mobile</h3>
            <span class="text-xs text-gray-500">Promo bar, nav, quick actions, drawer</span>
        </div>
        <form method="POST" action="{{ route($routePrefix.'.mobile') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo Text</label>
                    <textarea name="promo[text]" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('promo.text', $mobile['promo']['text'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo Text (AR)</label>
                    <textarea name="promo[text_ar]" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('promo.text_ar', $mobile['promo']['text_ar'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo Icon URL</label>
                    <input name="promo[icon]" value="{{ old('promo.icon', $mobile['promo']['icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="promo_icon_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo</label>
                    <input name="logo" value="{{ old('logo', $mobile['logo'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="logo_file" class="mt-2 w-full text-sm">
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Nav (scrollable)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @for($i=0; $i < 8; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="nav[{{ $i }}][label]" value="{{ old("nav.$i.label", $mobile['nav'][$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label (EN)">
                            <input name="nav[{{ $i }}][ar_label]" value="{{ old("nav.$i.ar_label", $mobile['nav'][$i]['ar_label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label (AR)" dir="rtl">
                            <input name="nav[{{ $i }}][url]" value="{{ old("nav.$i.url", $mobile['nav'][$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Quick Links (top buttons)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @for($i=0; $i < 3; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="quick_links[{{ $i }}][label]" value="{{ old("quick_links.$i.label", $mobile['quick_links'][$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label">
                            <input name="quick_links[{{ $i }}][icon]" value="{{ old("quick_links.$i.icon", $mobile['quick_links'][$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Icon">
                            <input name="quick_links[{{ $i }}][url]" value="{{ old("quick_links.$i.url", $mobile['quick_links'][$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Bottom Actions</h4>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @for($i=0; $i < 5; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="actions[{{ $i }}][label]" value="{{ old("actions.$i.label", $mobile['actions'][$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label">
                            <input name="actions[{{ $i }}][icon]" value="{{ old("actions.$i.icon", $mobile['actions'][$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Icon">
                            <input name="actions[{{ $i }}][url]" value="{{ old("actions.$i.url", $mobile['actions'][$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="URL">
                            <input name="actions[{{ $i }}][badge]" value="{{ old("actions.$i.badge", $mobile['actions'][$i]['badge'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="Badge">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Drawer Links</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i=0; $i < 7; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="drawer_links[{{ $i }}][label]" value="{{ old("drawer_links.$i.label", $mobile['drawer_links'][$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label">
                            <input name="drawer_links[{{ $i }}][url]" value="{{ old("drawer_links.$i.url", $mobile['drawer_links'][$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Drawer Social</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @for($i=0; $i < 4; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="drawer_social[{{ $i }}][platform]" value="{{ old("drawer_social.$i.platform", $mobile['drawer_social'][$i]['platform'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Platform">
                            <input name="drawer_social[{{ $i }}][url]" value="{{ old("drawer_social.$i.url", $mobile['drawer_social'][$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <h4 class="font-semibold mb-2">Drawer Legal</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i=0; $i < 2; $i++)
                        <div class="border rounded-lg p-3">
                            <input name="drawer_legal[{{ $i }}][label]" value="{{ old("drawer_legal.$i.label", $mobile['drawer_legal'][$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" placeholder="Label">
                            <input name="drawer_legal[{{ $i }}][url]" value="{{ old("drawer_legal.$i.url", $mobile['drawer_legal'][$i]['url'] ?? '') }}" class="w-full border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Mobile</button>
            </div>
        </form>
    </div>
</div>
@endsection
