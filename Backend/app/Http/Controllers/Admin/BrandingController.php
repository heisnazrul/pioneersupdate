<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandingController extends Controller
{
    private const APP_COURSE_ENGLISH = 'courseenglish';
    private const APP_UNIVERSITY = 'university';

    public function edit(): View
    {
        return $this->editByApp(self::APP_COURSE_ENGLISH);
    }

    public function editCourseEnglish(): View
    {
        return $this->editByApp(self::APP_COURSE_ENGLISH);
    }

    public function editUniversity(): View
    {
        return $this->editByApp(self::APP_UNIVERSITY);
    }

    public function updateHeader(Request $request): RedirectResponse
    {
        return $this->updateHeaderByApp($request, self::APP_COURSE_ENGLISH);
    }

    public function updateCourseEnglishHeader(Request $request): RedirectResponse
    {
        return $this->updateHeaderByApp($request, self::APP_COURSE_ENGLISH);
    }

    public function updateUniversityHeader(Request $request): RedirectResponse
    {
        return $this->updateHeaderByApp($request, self::APP_UNIVERSITY);
    }

    public function updateFooter(Request $request): RedirectResponse
    {
        return $this->updateFooterByApp($request, self::APP_COURSE_ENGLISH);
    }

    public function updateCourseEnglishFooter(Request $request): RedirectResponse
    {
        return $this->updateFooterByApp($request, self::APP_COURSE_ENGLISH);
    }

    public function updateUniversityFooter(Request $request): RedirectResponse
    {
        return $this->updateFooterByApp($request, self::APP_UNIVERSITY);
    }

    public function updateMobile(Request $request): RedirectResponse
    {
        return $this->updateMobileByApp($request, self::APP_COURSE_ENGLISH);
    }

    public function updateCourseEnglishMobile(Request $request): RedirectResponse
    {
        return $this->updateMobileByApp($request, self::APP_COURSE_ENGLISH);
    }

    public function updateUniversityMobile(Request $request): RedirectResponse
    {
        return $this->updateMobileByApp($request, self::APP_UNIVERSITY);
    }

    private function editByApp(string $app): View
    {
        $branding = Setting::get($this->brandingKey($app), []);

        return view('admin.branding.edit', [
            'branding' => $branding,
            'app' => $app,
        ]);
    }

    private function updateHeaderByApp(Request $request, string $app): RedirectResponse
    {
        $branding = Setting::get($this->brandingKey($app), []);

        $data = $request->validate([
            'logo_main_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'logo_ar_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'logo.main' => ['nullable', 'string', 'max:255'],
            'logo.ar' => ['nullable', 'string', 'max:255'],
            'top_nav' => ['nullable', 'array'],
            'top_nav.*.label' => ['nullable', 'string', 'max:255'],
            'top_nav.*.ar_label' => ['nullable', 'string', 'max:255'],
            'top_nav.*.url' => ['nullable', 'string', 'max:255'],
            'main_nav' => ['nullable', 'array'],
            'main_nav.*.label' => ['nullable', 'string', 'max:255'],
            'main_nav.*.ar_label' => ['nullable', 'string', 'max:255'],
            'main_nav.*.url' => ['nullable', 'string', 'max:255'],
            'currencies' => ['nullable', 'array'],
            'currencies.*.code' => ['nullable', 'string', 'max:10'],
            'currencies.*.label' => ['nullable', 'string', 'max:50'],
            'currencies.*.symbol' => ['nullable', 'string', 'max:10'],
            'currencies.*.icon' => ['nullable', 'string', 'max:255'],
            'languages' => ['nullable', 'array'],
            'languages.*.code' => ['nullable', 'string', 'max:10'],
            'languages.*.label' => ['nullable', 'string', 'max:50'],
            'languages.*.flag' => ['nullable', 'string', 'max:255'],
            'languages.*.flag_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'currencies.*.icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'buttons.compare.url' => ['nullable', 'string', 'max:255'],
            'buttons.compare.icon' => ['nullable', 'string', 'max:50'],
            'buttons.wishlist.url' => ['nullable', 'string', 'max:255'],
            'buttons.wishlist.icon' => ['nullable', 'string', 'max:50'],
            'buttons.account.url' => ['nullable', 'string', 'max:255'],
            'buttons.account.label' => ['nullable', 'string', 'max:255'],
            'buttons.account.ar_label' => ['nullable', 'string', 'max:255'],
            'buttons.account.icon' => ['nullable', 'string', 'max:50'],
        ]);

        // Handle uploads
        if ($request->hasFile('logo_main_file')) {
            $data['logo']['main'] = $this->storeUpload($request->file('logo_main_file'), 'logo-main');
        }
        if ($request->hasFile('logo_ar_file')) {
            $data['logo']['ar'] = $this->storeUpload($request->file('logo_ar_file'), 'logo-ar');
        }

        foreach ($data['currencies'] ?? [] as $idx => $currency) {
            if ($request->hasFile("currencies.$idx.icon_file")) {
                $data['currencies'][$idx]['icon'] = $this->storeUpload($request->file("currencies.$idx.icon_file"), 'currency-'.$idx);
            }
        }

        foreach ($data['languages'] ?? [] as $idx => $lang) {
            if ($request->hasFile("languages.$idx.flag_file")) {
                $data['languages'][$idx]['flag'] = $this->storeUpload($request->file("languages.$idx.flag_file"), 'lang-'.$idx);
            }
        }

        $branding['header'] = array_merge($branding['header'] ?? [], [
            'logo' => $data['logo'] ?? [],
            'top_nav' => $this->filterNav($data['top_nav'] ?? []),
            'main_nav' => $this->filterNav($data['main_nav'] ?? []),
            'currencies' => $this->filterOptions($data['currencies'] ?? []),
            'languages' => $this->filterOptions($data['languages'] ?? []),
            'buttons' => [
                'compare' => $data['buttons']['compare'] ?? ($branding['header']['buttons']['compare'] ?? []),
                'wishlist' => $data['buttons']['wishlist'] ?? ($branding['header']['buttons']['wishlist'] ?? []),
                'account' => $data['buttons']['account'] ?? ($branding['header']['buttons']['account'] ?? []),
            ],
        ]);

        Setting::put($this->brandingKey($app), $branding);

        return back()->with('success', 'Header branding updated');
    }

    private function updateFooterByApp(Request $request, string $app): RedirectResponse
    {
        $branding = Setting::get($this->brandingKey($app), []);

        $data = $request->validate([
            'footer_logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'subscribe.heading' => ['nullable', 'string', 'max:255'],
            'subscribe.placeholder' => ['nullable', 'string', 'max:255'],
            'subscribe.button_text' => ['nullable', 'string', 'max:255'],
            'columns' => ['nullable', 'array'],
            'columns.*.title' => ['nullable', 'string', 'max:255'],
            'columns.*.ar_title' => ['nullable', 'string', 'max:255'],
            'columns.*.items' => ['nullable', 'array'],
            'columns.*.items.*.label' => ['nullable', 'string', 'max:255'],
            'columns.*.items.*.ar_label' => ['nullable', 'string', 'max:255'],
            'columns.*.items.*.url' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'ar_description' => ['nullable', 'string', 'max:500'],
            'social' => ['nullable', 'array'],
            'social.*.platform' => ['nullable', 'string', 'max:50'],
            'social.*.url' => ['nullable', 'string', 'max:255'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'ar_copyright' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'ar_brand' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('footer_logo_file')) {
            $data['logo'] = $this->storeUpload($request->file('footer_logo_file'), 'footer-logo');
        }

        $branding['footer'] = array_merge($branding['footer'] ?? [], [
            'subscribe' => $data['subscribe'] ?? [],
            'columns' => $this->filterColumns($data['columns'] ?? []),
            'description' => $data['description'] ?? '',
            'ar_description' => $data['ar_description'] ?? '',
            'social' => $this->filterOptions($data['social'] ?? []),
            'copyright' => $data['copyright'] ?? '',
            'ar_copyright' => $data['ar_copyright'] ?? '',
            'brand' => $data['brand'] ?? '',
            'ar_brand' => $data['ar_brand'] ?? '',
            'logo' => $data['logo'] ?? ($branding['footer']['logo'] ?? ''),
        ]);

        Setting::put($this->brandingKey($app), $branding);

        return back()->with('success', 'Footer branding updated');
    }

    private function updateMobileByApp(Request $request, string $app): RedirectResponse
    {
        $branding = Setting::get($this->brandingKey($app), []);

        $data = $request->validate([
            'promo_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'promo.text' => ['nullable', 'string', 'max:500'],
            'promo.text_ar' => ['nullable', 'string', 'max:500'],
            'promo.icon' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
            'nav' => ['nullable', 'array'],
            'nav.*.label' => ['nullable', 'string', 'max:255'],
            'nav.*.ar_label' => ['nullable', 'string', 'max:255'],
            'nav.*.url' => ['nullable', 'string', 'max:255'],
            'quick_links' => ['nullable', 'array'],
            'quick_links.*.label' => ['nullable', 'string', 'max:255'],
            'quick_links.*.icon' => ['nullable', 'string', 'max:50'],
            'quick_links.*.url' => ['nullable', 'string', 'max:255'],
            'actions' => ['nullable', 'array'],
            'actions.*.label' => ['nullable', 'string', 'max:255'],
            'actions.*.icon' => ['nullable', 'string', 'max:50'],
            'actions.*.url' => ['nullable', 'string', 'max:255'],
            'actions.*.badge' => ['nullable', 'integer', 'min:0'],
            'drawer_links' => ['nullable', 'array'],
            'drawer_links.*.label' => ['nullable', 'string', 'max:255'],
            'drawer_links.*.url' => ['nullable', 'string', 'max:255'],
            'drawer_social' => ['nullable', 'array'],
            'drawer_social.*.platform' => ['nullable', 'string', 'max:50'],
            'drawer_social.*.url' => ['nullable', 'string', 'max:255'],
            'drawer_legal' => ['nullable', 'array'],
            'drawer_legal.*.label' => ['nullable', 'string', 'max:255'],
            'drawer_legal.*.url' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('promo_icon_file')) {
            $data['promo']['icon'] = $this->storeUpload($request->file('promo_icon_file'), 'promo-icon');
        }
        if ($request->hasFile('logo_file')) {
            $data['logo'] = $this->storeUpload($request->file('logo_file'), 'mobile-logo');
        }

        $branding['mobile'] = array_merge($branding['mobile'] ?? [], [
            'promo' => $data['promo'] ?? ($branding['mobile']['promo'] ?? []),
            'logo' => $data['logo'] ?? ($branding['mobile']['logo'] ?? ''),
            'nav' => $this->filterNav($data['nav'] ?? []),
            'quick_links' => $this->filterLinks($data['quick_links'] ?? []),
            'actions' => $this->filterActions($data['actions'] ?? []),
            'drawer_links' => $this->filterLinks($data['drawer_links'] ?? []),
            'drawer_social' => $this->filterOptions($data['drawer_social'] ?? []),
            'drawer_legal' => $this->filterLinks($data['drawer_legal'] ?? []),
        ]);

        Setting::put($this->brandingKey($app), $branding);

        return back()->with('success', 'Mobile branding updated');
    }

    private function brandingKey(string $app): string
    {
        return $app === self::APP_UNIVERSITY ? 'branding_university' : 'branding';
    }

    private function filterNav(array $items): array
    {
        return collect($items)->filter(fn ($i) => ($i['label'] ?? '') !== '' || ($i['url'] ?? '') !== '')
            ->map(fn ($i) => [
                'label' => $i['label'] ?? '',
                'ar_label' => $i['ar_label'] ?? '',
                'url' => $i['url'] ?? '',
            ])->values()->all();
    }

    private function filterColumns(array $columns): array
    {
        return collect($columns)->filter(fn ($c) => ($c['title'] ?? '') !== '' || ($c['ar_title'] ?? '') !== '')
            ->map(function ($c) {
                return [
                    'title' => $c['title'] ?? '',
                    'ar_title' => $c['ar_title'] ?? '',
                    'items' => $this->filterNav($c['items'] ?? []),
                ];
            })->values()->all();
    }

    private function filterOptions(array $options): array
    {
        return collect($options)->filter(fn ($o) => ($o['platform'] ?? $o['code'] ?? $o['label'] ?? '') !== '')
            ->map(fn ($o) => $o)->values()->all();
    }

    private function filterLinks(array $items): array
    {
        return collect($items)->filter(fn ($i) => ($i['label'] ?? '') !== '')
            ->map(fn ($i) => [
                'label' => $i['label'] ?? '',
                'url' => $i['url'] ?? '',
                'icon' => $i['icon'] ?? null,
            ])->values()->all();
    }

    private function filterActions(array $items): array
    {
        return collect($items)->filter(fn ($i) => ($i['label'] ?? '') !== '')
            ->map(fn ($i) => [
                'label' => $i['label'] ?? '',
                'icon' => $i['icon'] ?? '',
                'url' => $i['url'] ?? '',
                'badge' => $i['badge'] ?? null,
            ])->values()->all();
    }

    private function storeUpload($file, string $name): string
    {
        $safe = Str::slug($name).'-'.time().'.'.$file->getClientOriginalExtension();
        return $file->storeAs('branding', $safe, 'public');
    }
}
