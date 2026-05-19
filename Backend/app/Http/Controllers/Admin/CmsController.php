<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function courseEnglish(): View
    {
        return $this->indexByApp('courseenglish', 'CourseEnglish CMS');
    }

    public function university(): View
    {
        return $this->indexByApp('university', 'University CMS');
    }

    // --- CourseEnglish: Home page ---
    public function editCourseEnglishHome(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'home');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.home', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'stats' => $content['stats'] ?? [],
            'statsAr' => $arContent['stats'] ?? [],
            'statsMobile' => $content['stats_mobile'] ?? [],
            'statsMobileAr' => $arContent['stats_mobile'] ?? [],
            'certMeta' => $content['certificates_meta'] ?? [],
            'certMetaAr' => $arContent['certificates_meta'] ?? [],
            'partners' => $content['partners'] ?? [],
            'partnersAr' => $arContent['partners'] ?? [],
            'summer' => $content['summer'] ?? [],
            'summerAr' => $arContent['summer'] ?? [],
            'online' => $content['online'] ?? [],
            'onlineAr' => $arContent['online'] ?? [],
            'reviews' => $content['reviews'] ?? [],
            'reviewsAr' => $arContent['reviews'] ?? [],
            'destinations' => $content['destinations'] ?? [],
            'destinationsAr' => $arContent['destinations'] ?? [],
            'faq' => $content['faq'] ?? [],
            'faqAr' => $arContent['faq'] ?? [],
            'blogs' => $content['blogs'] ?? [],
            'blogsAr' => $arContent['blogs'] ?? [],
        ]);
    }

    public function updateCourseEnglishHomeHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');

        $data = $request->validate([
            'promo_text' => ['nullable', 'string', 'max:255'],
            'ar_promo_text' => ['nullable', 'string', 'max:255'],
            'promo_icon' => ['nullable', 'string', 'max:255'],
            'promo_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'headline' => ['required', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:500'],
            'promo' => ['nullable', 'string', 'max:500'],
            'services' => ['nullable', 'array'],
            'services.*' => ['nullable', 'string', 'max:120'],
            'ar_headline' => ['required', 'string', 'max:255'],
            'ar_subheadline' => ['nullable', 'string', 'max:500'],
            'ar_promo' => ['nullable', 'string', 'max:500'],
            'ar_services' => ['nullable', 'array'],
            'ar_services.*' => ['nullable', 'string', 'max:120'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'button_text_ar' => ['nullable', 'string', 'max:120'],
            'background_image' => ['nullable', 'string', 'max:255'],
            'background_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'figure_image' => ['nullable', 'string', 'max:255'],
            'figure_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'search_placeholder' => ['nullable', 'string', 'max:255'],
            'search_subtext' => ['nullable', 'string', 'max:255'],
            'ar_search_placeholder' => ['nullable', 'string', 'max:255'],
            'ar_search_subtext' => ['nullable', 'string', 'max:255'],
            'course_label' => ['nullable', 'string', 'max:120'],
            'course_placeholder' => ['nullable', 'string', 'max:255'],
            'ar_course_label' => ['nullable', 'string', 'max:120'],
            'ar_course_placeholder' => ['nullable', 'string', 'max:255'],
            'weeks_label' => ['nullable', 'string', 'max:120'],
            'weeks_placeholder' => ['nullable', 'string', 'max:255'],
            'ar_weeks_label' => ['nullable', 'string', 'max:120'],
            'ar_weeks_placeholder' => ['nullable', 'string', 'max:255'],
            'start_label' => ['nullable', 'string', 'max:120'],
            'start_placeholder' => ['nullable', 'string', 'max:255'],
            'ar_start_label' => ['nullable', 'string', 'max:120'],
            'ar_start_placeholder' => ['nullable', 'string', 'max:255'],
            'search_button_text' => ['nullable', 'string', 'max:120'],
            'ar_search_button_text' => ['nullable', 'string', 'max:120'],
        ]);

        if ($request->hasFile('promo_icon_file')) {
            $data['promo_icon'] = $this->storeUpload($request->file('promo_icon_file'), 'home-promo-icon');
        }
        if ($request->hasFile('background_image_file')) {
            $data['background_image'] = $this->storeUpload($request->file('background_image_file'), 'home-bg');
        }
        if ($request->hasFile('figure_image_file')) {
            $data['figure_image'] = $this->storeUpload($request->file('figure_image_file'), 'home-figure');
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'promo_text' => $data['promo_text'] ?? '',
            'promo_icon' => $data['promo_icon'] ?? '',
            'headline' => $data['headline'],
            'subheadline' => $data['subheadline'] ?? '',
            'promo' => $data['promo'] ?? '',
            'services' => array_values(array_filter($data['services'] ?? [])),
            'button_text' => $data['button_text'] ?? '',
            'background_image' => $data['background_image'] ?? '',
            'figure_image' => $data['figure_image'] ?? '',
            'search_placeholder' => $data['search_placeholder'] ?? '',
            'search_subtext' => $data['search_subtext'] ?? '',
            'course_label' => $data['course_label'] ?? '',
            'course_placeholder' => $data['course_placeholder'] ?? '',
            'weeks_label' => $data['weeks_label'] ?? '',
            'weeks_placeholder' => $data['weeks_placeholder'] ?? '',
            'start_label' => $data['start_label'] ?? '',
            'start_placeholder' => $data['start_placeholder'] ?? '',
            'search_button_text' => $data['search_button_text'] ?? '',
        ];

        $arContent['hero'] = [
            'promo_text' => $data['ar_promo_text'] ?? ($data['promo_text'] ?? ''),
            'promo_icon' => $data['promo_icon'] ?? '',
            'headline' => $data['ar_headline'],
            'subheadline' => $data['ar_subheadline'] ?? '',
            'promo' => $data['ar_promo'] ?? '',
            'services' => array_values(array_filter($data['ar_services'] ?? [])),
            'button_text' => $data['button_text_ar'] ?? ($data['button_text'] ?? ''),
            'background_image' => $data['background_image'] ?? '',
            'figure_image' => $data['figure_image'] ?? '',
            'search_placeholder' => $data['ar_search_placeholder'] ?? ($data['search_placeholder'] ?? ''),
            'search_subtext' => $data['ar_search_subtext'] ?? ($data['search_subtext'] ?? ''),
            'course_label' => $data['ar_course_label'] ?? ($data['course_label'] ?? ''),
            'course_placeholder' => $data['ar_course_placeholder'] ?? ($data['course_placeholder'] ?? ''),
            'weeks_label' => $data['ar_weeks_label'] ?? ($data['weeks_label'] ?? ''),
            'weeks_placeholder' => $data['ar_weeks_placeholder'] ?? ($data['weeks_placeholder'] ?? ''),
            'start_label' => $data['ar_start_label'] ?? ($data['start_label'] ?? ''),
            'start_placeholder' => $data['ar_start_placeholder'] ?? ($data['start_placeholder'] ?? ''),
            'search_button_text' => $data['ar_search_button_text'] ?? ($data['search_button_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Hero section updated');
    }

    public function updateCourseEnglishHomeStats(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:500'],
            'body_ar' => ['nullable', 'string', 'max:500'],
            'stats' => ['required', 'array', 'min:1'],
            'stats.*.value' => ['required', 'string', 'max:50'],
            'stats.*.label' => ['required', 'string', 'max:255'],
            'stats.*.ar_label' => ['required', 'string', 'max:255'],
            'heading_mobile' => ['nullable', 'string', 'max:255'],
            'heading_mobile_ar' => ['nullable', 'string', 'max:255'],
            'body_mobile' => ['nullable', 'string', 'max:500'],
            'body_mobile_ar' => ['nullable', 'string', 'max:500'],
            'stats_mobile' => ['nullable', 'array'],
            'stats_mobile.*.value' => ['required_with:stats_mobile', 'string', 'max:50'],
            'stats_mobile.*.label' => ['required_with:stats_mobile', 'string', 'max:255'],
            'stats_mobile.*.ar_label' => ['required_with:stats_mobile', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);
        $existingMobile = $content['stats_mobile'] ?? [];
        $existingMobileAr = $arContent['stats_mobile'] ?? [];

        $content['stats'] = [
            'heading' => $data['heading'] ?? '',
            'body' => $data['body'] ?? '',
            'items' => collect($data['stats'])->map(fn($item) => [
                'value' => $item['value'],
                'label' => $item['label'],
            ])->values()->all(),
        ];

        $arContent['stats'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'body' => $data['body_ar'] ?? ($data['body'] ?? ''),
            'items' => collect($data['stats'])->map(fn($item) => [
                'value' => $item['value'],
                'label' => $item['ar_label'],
            ])->values()->all(),
        ];

        $content['stats_mobile'] = [
            'heading' => $data['heading_mobile'] ?? ($existingMobile['heading'] ?? ($content['stats']['heading'] ?? '')),
            'body' => $data['body_mobile'] ?? ($existingMobile['body'] ?? ($content['stats']['body'] ?? '')),
            'items' => $data['stats_mobile'] ?? ($existingMobile['items'] ?? $content['stats']['items'] ?? []),
        ];

        $arContent['stats_mobile'] = [
            'heading' => $data['heading_mobile_ar'] ?? ($existingMobileAr['heading'] ?? ($arContent['stats']['heading'] ?? $content['stats_mobile']['heading'] ?? '')),
            'body' => $data['body_mobile_ar'] ?? ($existingMobileAr['body'] ?? ($arContent['stats']['body'] ?? $content['stats_mobile']['body'] ?? '')),
            'items' => $data['stats_mobile']
                ? collect($data['stats_mobile'])->map(fn($item) => [
                    'value' => $item['value'],
                    'label' => $item['ar_label'] ?? $item['label'] ?? '',
                ])->values()->all()
                : ($existingMobileAr['items'] ?? $arContent['stats']['items'] ?? $content['stats_mobile']['items'] ?? []),
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Stats section updated');
    }

    public function updateCourseEnglishHomeCertificates(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['certificates_meta'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
        ];

        $arContent['certificates_meta'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Certificates text updated (cards stay dynamic)');
    }

    public function updateCourseEnglishHomePartners(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'view_all_label' => ['nullable', 'string', 'max:120'],
            'view_all_label_ar' => ['nullable', 'string', 'max:120'],
            'view_all_url' => ['nullable', 'string', 'max:255'],
            'arrow_left_icon' => ['nullable', 'string', 'max:255'],
            'arrow_right_icon' => ['nullable', 'string', 'max:255'],
            'arrow_left_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'arrow_right_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
        ]);

        if ($request->hasFile('arrow_left_icon_file')) {
            $data['arrow_left_icon'] = $this->storeUpload($request->file('arrow_left_icon_file'), 'partners-arrow-left');
        }
        if ($request->hasFile('arrow_right_icon_file')) {
            $data['arrow_right_icon'] = $this->storeUpload($request->file('arrow_right_icon_file'), 'partners-arrow-right');
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['partners'] = [
            'heading' => $data['heading'] ?? '',
            'view_all_label' => $data['view_all_label'] ?? '',
            'view_all_url' => $data['view_all_url'] ?? '',
            'arrow_left_icon' => $data['arrow_left_icon'] ?? '',
            'arrow_right_icon' => $data['arrow_right_icon'] ?? '',
        ];
        $arContent['partners'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'view_all_label' => $data['view_all_label_ar'] ?? ($data['view_all_label'] ?? ''),
            'view_all_url' => $data['view_all_url'] ?? '',
            'arrow_left_icon' => $data['arrow_left_icon'] ?? '',
            'arrow_right_icon' => $data['arrow_right_icon'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Partner institutes section updated');
    }

    public function updateCourseEnglishHomeSummer(Request $request): RedirectResponse
    {
        return $this->updateHeadingCtaSection($request, 'summer', 'Summer programs section updated', true);
    }

    public function updateCourseEnglishHomeOnline(Request $request): RedirectResponse
    {
        return $this->updateHeadingCtaSection($request, 'online', 'Online courses section updated', true);
    }

    public function updateCourseEnglishHomeReviews(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
        ]);
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);
        $content['reviews'] = ['heading' => $data['heading'] ?? ''];
        $arContent['reviews'] = ['heading' => $data['heading_ar'] ?? ($data['heading'] ?? '')];
        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Reviews section updated');
    }

    public function updateCourseEnglishHomeDestinations(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'links' => ['nullable', 'array'],
            'links.*.label' => ['nullable', 'string', 'max:255'],
            'links.*.url' => ['nullable', 'string', 'max:255'],
            'links_ar' => ['nullable', 'array'],
            'links_ar.*.label' => ['nullable', 'string', 'max:255'],
            'links_ar.*.url' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['destinations'] = [
            'heading' => $data['heading'] ?? '',
            'links' => $this->filterLinksSimple($data['links'] ?? []),
        ];
        $arContent['destinations'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'links' => $this->filterLinksSimple($data['links_ar'] ?? []),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Destinations section updated');
    }

    public function updateCourseEnglishHomeFaq(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_text_ar' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'cta_icon' => ['nullable', 'string', 'max:255'],
            'cta_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'help_text' => ['nullable', 'string', 'max:255'],
            'help_text_ar' => ['nullable', 'string', 'max:255'],
            'show_more_label' => ['nullable', 'string', 'max:255'],
            'show_more_label_ar' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('cta_icon_file')) {
            $data['cta_icon'] = $this->storeUpload($request->file('cta_icon_file'), 'faq-cta-icon');
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['faq'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'cta_text' => $data['cta_text'] ?? '',
            'cta_url' => $data['cta_url'] ?? '',
            'cta_icon' => $data['cta_icon'] ?? '',
            'help_text' => $data['help_text'] ?? '',
            'show_more_label' => $data['show_more_label'] ?? '',
        ];
        $arContent['faq'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'cta_text' => $data['cta_text_ar'] ?? ($data['cta_text'] ?? ''),
            'cta_url' => $data['cta_url'] ?? '',
            'cta_icon' => $data['cta_icon'] ?? '',
            'help_text' => $data['help_text_ar'] ?? ($data['help_text'] ?? ''),
            'show_more_label' => $data['show_more_label_ar'] ?? ($data['show_more_label'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'FAQ section updated');
    }

    public function updateCourseEnglishHomeBlogs(Request $request): RedirectResponse
    {
        return $this->updateHeadingCtaSection($request, 'blogs', 'Blogs section updated', true);
    }

    // -------- Offers Page --------
    public function editCourseEnglishOffers(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'offers');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.offers', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'sections' => $content['sections'] ?? [],
            'sectionsAr' => $arContent['sections'] ?? [],
        ]);
    }

    public function updateCourseEnglishOffers(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'offers');

        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:120'],
            'badge_ar' => ['nullable', 'string', 'max:120'],
            'headline' => ['nullable', 'string', 'max:255'],
            'headline_ar' => ['nullable', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:500'],
            'subheadline_ar' => ['nullable', 'string', 'max:500'],
            'sections' => ['nullable', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.title_ar' => ['nullable', 'string', 'max:255'],
            'sections.*.subtitle' => ['nullable', 'string', 'max:255'],
            'sections.*.subtitle_ar' => ['nullable', 'string', 'max:255'],
            'sections.*.link' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['badge'] ?? '',
            'headline' => $data['headline'] ?? '',
            'subheadline' => $data['subheadline'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['badge_ar'] ?? ($data['badge'] ?? ''),
            'headline' => $data['headline_ar'] ?? ($data['headline'] ?? ''),
            'subheadline' => $data['subheadline_ar'] ?? ($data['subheadline'] ?? ''),
        ];

        $sections = collect($data['sections'] ?? [])->map(function ($item) {
            return [
                'title' => $item['title'] ?? '',
                'subtitle' => $item['subtitle'] ?? '',
                'link' => $item['link'] ?? '',
            ];
        })->values()->all();
        $sectionsAr = collect($data['sections'] ?? [])->map(function ($item) {
            return [
                'title' => $item['title_ar'] ?? ($item['title'] ?? ''),
                'subtitle' => $item['subtitle_ar'] ?? ($item['subtitle'] ?? ''),
                'link' => $item['link'] ?? '',
            ];
        })->values()->all();

        $content['sections'] = $sections;
        $arContent['sections'] = $sectionsAr;

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Offers page updated');
    }

    // -------- About Us Page --------
    public function editCourseEnglishAbout(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'about-us');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.about', [
            'page' => $page,
            'content' => $content,
            'arContent' => $arContent,
        ]);
    }

    public function updateCourseEnglishAbout(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'about-us');

        $data = $request->validate([
            'breadcrumb.home' => ['nullable', 'string', 'max:120'],
            'breadcrumb.home_ar' => ['nullable', 'string', 'max:120'],
            'breadcrumb.current' => ['nullable', 'string', 'max:120'],
            'breadcrumb.current_ar' => ['nullable', 'string', 'max:120'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'page_title_ar' => ['nullable', 'string', 'max:255'],
            'intro.title' => ['nullable', 'string', 'max:255'],
            'intro.title_ar' => ['nullable', 'string', 'max:255'],
            'intro.paragraph_1' => ['nullable', 'string', 'max:2000'],
            'intro.paragraph_1_ar' => ['nullable', 'string', 'max:2000'],
            'intro.paragraph_2' => ['nullable', 'string', 'max:2000'],
            'intro.paragraph_2_ar' => ['nullable', 'string', 'max:2000'],
            'intro.image' => ['nullable', 'string', 'max:255'],
            'intro.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'vision.title' => ['nullable', 'string', 'max:255'],
            'vision.title_ar' => ['nullable', 'string', 'max:255'],
            'vision.body' => ['nullable', 'string', 'max:2000'],
            'vision.body_ar' => ['nullable', 'string', 'max:2000'],
            'mission.title' => ['nullable', 'string', 'max:255'],
            'mission.title_ar' => ['nullable', 'string', 'max:255'],
            'mission.body' => ['nullable', 'string', 'max:2000'],
            'mission.body_ar' => ['nullable', 'string', 'max:2000'],
            'why.title' => ['nullable', 'string', 'max:255'],
            'why.title_ar' => ['nullable', 'string', 'max:255'],
            'why.subtitle' => ['nullable', 'string', 'max:500'],
            'why.subtitle_ar' => ['nullable', 'string', 'max:500'],
            'why.items' => ['nullable', 'array'],
            'why.items.*.title' => ['nullable', 'string', 'max:255'],
            'why.items.*.title_ar' => ['nullable', 'string', 'max:255'],
            'why.items.*.body' => ['nullable', 'string', 'max:1000'],
            'why.items.*.body_ar' => ['nullable', 'string', 'max:1000'],
            'why.items.*.icon' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('intro.image_file')) {
            $data['intro']['image'] = $this->storeUpload($request->file('intro.image_file'), 'courseenglish-about-intro');
        }

        $whyItems = collect($data['why']['items'] ?? [])->values()->map(function (array $item) {
            return [
                'title' => $item['title'] ?? '',
                'body' => $item['body'] ?? '',
                'icon' => $item['icon'] ?? '',
            ];
        })->all();
        $whyItemsAr = collect($data['why']['items'] ?? [])->values()->map(function (array $item) {
            return [
                'title' => $item['title_ar'] ?? ($item['title'] ?? ''),
                'body' => $item['body_ar'] ?? ($item['body'] ?? ''),
                'icon' => $item['icon'] ?? '',
            ];
        })->all();

        $content = [
            'breadcrumb' => [
                'home' => $data['breadcrumb']['home'] ?? '',
                'current' => $data['breadcrumb']['current'] ?? '',
            ],
            'page_title' => $data['page_title'] ?? '',
            'intro' => [
                'title' => $data['intro']['title'] ?? '',
                'paragraph_1' => $data['intro']['paragraph_1'] ?? '',
                'paragraph_2' => $data['intro']['paragraph_2'] ?? '',
                'image' => $data['intro']['image'] ?? '',
            ],
            'vision' => [
                'title' => $data['vision']['title'] ?? '',
                'body' => $data['vision']['body'] ?? '',
            ],
            'mission' => [
                'title' => $data['mission']['title'] ?? '',
                'body' => $data['mission']['body'] ?? '',
            ],
            'why' => [
                'title' => $data['why']['title'] ?? '',
                'subtitle' => $data['why']['subtitle'] ?? '',
                'items' => $whyItems,
            ],
        ];

        $arContent = [
            'breadcrumb' => [
                'home' => $data['breadcrumb']['home_ar'] ?? ($data['breadcrumb']['home'] ?? ''),
                'current' => $data['breadcrumb']['current_ar'] ?? ($data['breadcrumb']['current'] ?? ''),
            ],
            'page_title' => $data['page_title_ar'] ?? ($data['page_title'] ?? ''),
            'intro' => [
                'title' => $data['intro']['title_ar'] ?? ($data['intro']['title'] ?? ''),
                'paragraph_1' => $data['intro']['paragraph_1_ar'] ?? ($data['intro']['paragraph_1'] ?? ''),
                'paragraph_2' => $data['intro']['paragraph_2_ar'] ?? ($data['intro']['paragraph_2'] ?? ''),
                'image' => $data['intro']['image'] ?? '',
            ],
            'vision' => [
                'title' => $data['vision']['title_ar'] ?? ($data['vision']['title'] ?? ''),
                'body' => $data['vision']['body_ar'] ?? ($data['vision']['body'] ?? ''),
            ],
            'mission' => [
                'title' => $data['mission']['title_ar'] ?? ($data['mission']['title'] ?? ''),
                'body' => $data['mission']['body_ar'] ?? ($data['mission']['body'] ?? ''),
            ],
            'why' => [
                'title' => $data['why']['title_ar'] ?? ($data['why']['title'] ?? ''),
                'subtitle' => $data['why']['subtitle_ar'] ?? ($data['why']['subtitle'] ?? ''),
                'items' => $whyItemsAr,
            ],
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'About page updated');
    }

    // -------- Language Institutes Page --------
    public function editCourseEnglishLanguageInstitutes(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institutes');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.language-institutes', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'results' => $content['results'] ?? [],
            'resultsAr' => $arContent['results'] ?? [],
            'sidebar' => $content['sidebar'] ?? [],
            'sidebarAr' => $arContent['sidebar'] ?? [],
            'loadMore' => $content['load_more'] ?? [],
            'loadMoreAr' => $arContent['load_more'] ?? [],
        ]);
    }

    public function updateCourseEnglishLanguageInstitutesHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institutes');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'destination_label' => ['nullable', 'string', 'max:120'],
            'destination_placeholder' => ['nullable', 'string', 'max:255'],
            'course_label' => ['nullable', 'string', 'max:120'],
            'course_placeholder' => ['nullable', 'string', 'max:255'],
            'course_options' => ['nullable', 'array'],
            'course_options.*' => ['nullable', 'string', 'max:120'],
            'course_options_ar' => ['nullable', 'array'],
            'course_options_ar.*' => ['nullable', 'string', 'max:120'],
            'duration_label' => ['nullable', 'string', 'max:120'],
            'duration_placeholder' => ['nullable', 'string', 'max:255'],
            'start_label' => ['nullable', 'string', 'max:120'],
            'start_placeholder' => ['nullable', 'string', 'max:255'],
            'search_aria' => ['nullable', 'string', 'max:120'],
            'search_aria_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'destination_label' => $data['destination_label'] ?? '',
            'destination_placeholder' => $data['destination_placeholder'] ?? '',
            'course_label' => $data['course_label'] ?? '',
            'course_placeholder' => $data['course_placeholder'] ?? '',
            'course_options' => array_values(array_filter($data['course_options'] ?? [])),
            'duration_label' => $data['duration_label'] ?? '',
            'duration_placeholder' => $data['duration_placeholder'] ?? '',
            'start_label' => $data['start_label'] ?? '',
            'start_placeholder' => $data['start_placeholder'] ?? '',
            'search_aria' => $data['search_aria'] ?? '',
        ];
        $arContent['hero'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'destination_label' => $data['destination_label'] ?? '',
            'destination_placeholder' => $data['destination_placeholder'] ?? '',
            'course_label' => $data['course_label'] ?? '',
            'course_placeholder' => $data['course_placeholder'] ?? '',
            'course_options' => array_values(array_filter($data['course_options_ar'] ?? ($data['course_options'] ?? []))),
            'duration_label' => $data['duration_label'] ?? '',
            'duration_placeholder' => $data['duration_placeholder'] ?? '',
            'start_label' => $data['start_label'] ?? '',
            'start_placeholder' => $data['start_placeholder'] ?? '',
            'search_aria' => $data['search_aria_ar'] ?? ($data['search_aria'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Hero section updated');
    }

    public function updateCourseEnglishLanguageInstitutesResults(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institutes');

        $data = $request->validate([
            'count_label' => ['nullable', 'string', 'max:255'],
            'count_label_ar' => ['nullable', 'string', 'max:255'],
            'step_label' => ['nullable', 'string', 'max:255'],
            'step_label_ar' => ['nullable', 'string', 'max:255'],
            'sort_label' => ['nullable', 'string', 'max:120'],
            'sort_label_ar' => ['nullable', 'string', 'max:120'],
            'sort_options' => ['nullable', 'array'],
            'sort_options.*' => ['nullable', 'string', 'max:120'],
            'sort_options_ar' => ['nullable', 'array'],
            'sort_options_ar.*' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['results'] = [
            'count_label' => $data['count_label'] ?? '',
            'step_label' => $data['step_label'] ?? '',
            'sort_label' => $data['sort_label'] ?? '',
            'sort_options' => array_values(array_filter($data['sort_options'] ?? [])),
        ];
        $arContent['results'] = [
            'count_label' => $data['count_label_ar'] ?? ($data['count_label'] ?? ''),
            'step_label' => $data['step_label_ar'] ?? ($data['step_label'] ?? ''),
            'sort_label' => $data['sort_label_ar'] ?? ($data['sort_label'] ?? ''),
            'sort_options' => array_values(array_filter($data['sort_options_ar'] ?? ($data['sort_options'] ?? []))),
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Results header updated');
    }

    public function updateCourseEnglishLanguageInstitutesSidebar(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institutes');

        $data = $request->validate([
            'sections' => ['nullable', 'array'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.title_ar' => ['nullable', 'string', 'max:255'],
            'sections.*.options' => ['nullable', 'array'],
            'sections.*.options.*' => ['nullable', 'string', 'max:120'],
            'sections.*.options_ar' => ['nullable', 'array'],
            'sections.*.options_ar.*' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $sections = collect($data['sections'] ?? [])->map(function ($item) {
            return [
                'title' => $item['title'] ?? '',
                'options' => array_values(array_filter($item['options'] ?? [])),
            ];
        })->values()->all();

        $sectionsAr = collect($data['sections'] ?? [])->map(function ($item) {
            return [
                'title' => $item['title_ar'] ?? ($item['title'] ?? ''),
                'options' => array_values(array_filter($item['options_ar'] ?? ($item['options'] ?? []))),
            ];
        })->values()->all();

        $content['sidebar'] = [
            'sections' => $sections,
        ];
        $arContent['sidebar'] = [
            'sections' => $sectionsAr,
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Sidebar filters updated');
    }

    public function updateCourseEnglishLanguageInstitutesLoadMore(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institutes');

        $data = $request->validate([
            'text' => ['nullable', 'string', 'max:120'],
            'text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['load_more'] = [
            'text' => $data['text'] ?? '',
        ];
        $arContent['load_more'] = [
            'text' => $data['text_ar'] ?? ($data['text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Load more button updated');
    }

    // -------- Language Institute Detail Page --------
    public function editCourseEnglishLanguageInstituteDetail(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.language-institute-detail', [
            'page' => $page,
            'topNav' => $content['top_nav'] ?? [],
            'topNavAr' => $arContent['top_nav'] ?? [],
            'courseStep' => $content['course_step'] ?? [],
            'courseStepAr' => $arContent['course_step'] ?? [],
            'accommodationStep' => $content['accommodation_step'] ?? [],
            'accommodationStepAr' => $arContent['accommodation_step'] ?? [],
            'additionalOptions' => $content['additional_options'] ?? [],
            'additionalOptionsAr' => $arContent['additional_options'] ?? [],
            'sidebarInquiry' => $content['sidebar_inquiry'] ?? [],
            'sidebarInquiryAr' => $arContent['sidebar_inquiry'] ?? [],
            'bookingSummary' => $content['booking_summary'] ?? [],
            'bookingSummaryAr' => $arContent['booking_summary'] ?? [],
        ]);
    }

    public function updateCourseEnglishLanguageInstituteDetailTopNav(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');

        $data = $request->validate([
            'back_label' => ['nullable', 'string', 'max:120'],
            'back_label_ar' => ['nullable', 'string', 'max:120'],
            'compare_label' => ['nullable', 'string', 'max:120'],
            'compare_label_ar' => ['nullable', 'string', 'max:120'],
            'save_label' => ['nullable', 'string', 'max:120'],
            'save_label_ar' => ['nullable', 'string', 'max:120'],
            'share_label' => ['nullable', 'string', 'max:120'],
            'share_label_ar' => ['nullable', 'string', 'max:120'],
            'back_icon' => ['nullable', 'string', 'max:255'],
            'back_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'compare_icon' => ['nullable', 'string', 'max:255'],
            'compare_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'save_icon' => ['nullable', 'string', 'max:255'],
            'save_icon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        if ($request->hasFile('back_icon_file')) {
            $data['back_icon'] = $this->storeUpload($request->file('back_icon_file'), 'detail-back-icon');
        }
        if ($request->hasFile('compare_icon_file')) {
            $data['compare_icon'] = $this->storeUpload($request->file('compare_icon_file'), 'detail-compare-icon');
        }
        if ($request->hasFile('save_icon_file')) {
            $data['save_icon'] = $this->storeUpload($request->file('save_icon_file'), 'detail-save-icon');
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['top_nav'] = [
            'back_label' => $data['back_label'] ?? '',
            'compare_label' => $data['compare_label'] ?? '',
            'save_label' => $data['save_label'] ?? '',
            'share_label' => $data['share_label'] ?? '',
            'back_icon' => $data['back_icon'] ?? '',
            'compare_icon' => $data['compare_icon'] ?? '',
            'save_icon' => $data['save_icon'] ?? '',
        ];
        $arContent['top_nav'] = [
            'back_label' => $data['back_label_ar'] ?? ($data['back_label'] ?? ''),
            'compare_label' => $data['compare_label_ar'] ?? ($data['compare_label'] ?? ''),
            'save_label' => $data['save_label_ar'] ?? ($data['save_label'] ?? ''),
            'share_label' => $data['share_label_ar'] ?? ($data['share_label'] ?? ''),
            'back_icon' => $data['back_icon'] ?? '',
            'compare_icon' => $data['compare_icon'] ?? '',
            'save_icon' => $data['save_icon'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Top nav updated');
    }

    public function updateCourseEnglishLanguageInstituteDetailCourseStep(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'lessons_label' => ['nullable', 'string', 'max:120'],
            'lessons_label_ar' => ['nullable', 'string', 'max:120'],
            'hours_label' => ['nullable', 'string', 'max:120'],
            'hours_label_ar' => ['nullable', 'string', 'max:120'],
            'age_label' => ['nullable', 'string', 'max:120'],
            'age_label_ar' => ['nullable', 'string', 'max:120'],
            'level_label' => ['nullable', 'string', 'max:120'],
            'level_label_ar' => ['nullable', 'string', 'max:120'],
            'price_suffix' => ['nullable', 'string', 'max:50'],
            'price_suffix_ar' => ['nullable', 'string', 'max:50'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['course_step'] = [
            'title' => $data['title'] ?? '',
            'lessons_label' => $data['lessons_label'] ?? '',
            'hours_label' => $data['hours_label'] ?? '',
            'age_label' => $data['age_label'] ?? '',
            'level_label' => $data['level_label'] ?? '',
            'price_suffix' => $data['price_suffix'] ?? '',
        ];
        $arContent['course_step'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'lessons_label' => $data['lessons_label_ar'] ?? ($data['lessons_label'] ?? ''),
            'hours_label' => $data['hours_label_ar'] ?? ($data['hours_label'] ?? ''),
            'age_label' => $data['age_label_ar'] ?? ($data['age_label'] ?? ''),
            'level_label' => $data['level_label_ar'] ?? ($data['level_label'] ?? ''),
            'price_suffix' => $data['price_suffix_ar'] ?? ($data['price_suffix'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Course step updated');
    }

    public function updateCourseEnglishLanguageInstituteDetailAccommodationStep(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'optional_label' => ['nullable', 'string', 'max:120'],
            'optional_label_ar' => ['nullable', 'string', 'max:120'],
            'no_accommodation_title' => ['nullable', 'string', 'max:255'],
            'no_accommodation_title_ar' => ['nullable', 'string', 'max:255'],
            'no_accommodation_description' => ['nullable', 'string', 'max:500'],
            'no_accommodation_description_ar' => ['nullable', 'string', 'max:500'],
            'price_suffix' => ['nullable', 'string', 'max:50'],
            'price_suffix_ar' => ['nullable', 'string', 'max:50'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['accommodation_step'] = [
            'title' => $data['title'] ?? '',
            'optional_label' => $data['optional_label'] ?? '',
            'no_accommodation_title' => $data['no_accommodation_title'] ?? '',
            'no_accommodation_description' => $data['no_accommodation_description'] ?? '',
            'price_suffix' => $data['price_suffix'] ?? '',
        ];
        $arContent['accommodation_step'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'optional_label' => $data['optional_label_ar'] ?? ($data['optional_label'] ?? ''),
            'no_accommodation_title' => $data['no_accommodation_title_ar'] ?? ($data['no_accommodation_title'] ?? ''),
            'no_accommodation_description' => $data['no_accommodation_description_ar'] ?? ($data['no_accommodation_description'] ?? ''),
            'price_suffix' => $data['price_suffix_ar'] ?? ($data['price_suffix'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Accommodation step updated');
    }

    public function updateCourseEnglishLanguageInstituteDetailAdditionalOptions(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['additional_options'] = [
            'heading' => $data['heading'] ?? '',
        ];
        $arContent['additional_options'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Additional options heading updated');
    }

    public function updateCourseEnglishLanguageInstituteDetailSidebar(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'button_text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['sidebar_inquiry'] = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'button_text' => $data['button_text'] ?? '',
        ];
        $arContent['sidebar_inquiry'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
            'button_text' => $data['button_text_ar'] ?? ($data['button_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Sidebar inquiry updated');
    }

    public function updateCourseEnglishLanguageInstituteDetailBookingSummary(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'language-institute-detail');

        $data = $request->validate([
            'total_label' => ['nullable', 'string', 'max:255'],
            'total_label_ar' => ['nullable', 'string', 'max:255'],
            'study_dates_label' => ['nullable', 'string', 'max:255'],
            'study_dates_label_ar' => ['nullable', 'string', 'max:255'],
            'duration_label' => ['nullable', 'string', 'max:255'],
            'duration_label_ar' => ['nullable', 'string', 'max:255'],
            'coupon_label' => ['nullable', 'string', 'max:255'],
            'coupon_label_ar' => ['nullable', 'string', 'max:255'],
            'coupon_placeholder' => ['nullable', 'string', 'max:255'],
            'coupon_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'coupon_apply_text' => ['nullable', 'string', 'max:120'],
            'coupon_apply_text_ar' => ['nullable', 'string', 'max:120'],
            'course_summary_label' => ['nullable', 'string', 'max:255'],
            'course_summary_label_ar' => ['nullable', 'string', 'max:255'],
            'accommodation_summary_label' => ['nullable', 'string', 'max:255'],
            'accommodation_summary_label_ar' => ['nullable', 'string', 'max:255'],
            'registration_fee_label' => ['nullable', 'string', 'max:255'],
            'registration_fee_label_ar' => ['nullable', 'string', 'max:255'],
            'course_discount_label' => ['nullable', 'string', 'max:255'],
            'course_discount_label_ar' => ['nullable', 'string', 'max:255'],
            'foundation_discount_label' => ['nullable', 'string', 'max:255'],
            'foundation_discount_label_ar' => ['nullable', 'string', 'max:255'],
            'total_discount_label' => ['nullable', 'string', 'max:255'],
            'total_discount_label_ar' => ['nullable', 'string', 'max:255'],
            'confirm_button_text' => ['nullable', 'string', 'max:120'],
            'confirm_button_text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['booking_summary'] = [
            'total_label' => $data['total_label'] ?? '',
            'study_dates_label' => $data['study_dates_label'] ?? '',
            'duration_label' => $data['duration_label'] ?? '',
            'coupon_label' => $data['coupon_label'] ?? '',
            'coupon_placeholder' => $data['coupon_placeholder'] ?? '',
            'coupon_apply_text' => $data['coupon_apply_text'] ?? '',
            'course_summary_label' => $data['course_summary_label'] ?? '',
            'accommodation_summary_label' => $data['accommodation_summary_label'] ?? '',
            'registration_fee_label' => $data['registration_fee_label'] ?? '',
            'course_discount_label' => $data['course_discount_label'] ?? '',
            'foundation_discount_label' => $data['foundation_discount_label'] ?? '',
            'total_discount_label' => $data['total_discount_label'] ?? '',
            'confirm_button_text' => $data['confirm_button_text'] ?? '',
        ];
        $arContent['booking_summary'] = [
            'total_label' => $data['total_label_ar'] ?? ($data['total_label'] ?? ''),
            'study_dates_label' => $data['study_dates_label_ar'] ?? ($data['study_dates_label'] ?? ''),
            'duration_label' => $data['duration_label_ar'] ?? ($data['duration_label'] ?? ''),
            'coupon_label' => $data['coupon_label_ar'] ?? ($data['coupon_label'] ?? ''),
            'coupon_placeholder' => $data['coupon_placeholder_ar'] ?? ($data['coupon_placeholder'] ?? ''),
            'coupon_apply_text' => $data['coupon_apply_text_ar'] ?? ($data['coupon_apply_text'] ?? ''),
            'course_summary_label' => $data['course_summary_label_ar'] ?? ($data['course_summary_label'] ?? ''),
            'accommodation_summary_label' => $data['accommodation_summary_label_ar'] ?? ($data['accommodation_summary_label'] ?? ''),
            'registration_fee_label' => $data['registration_fee_label_ar'] ?? ($data['registration_fee_label'] ?? ''),
            'course_discount_label' => $data['course_discount_label_ar'] ?? ($data['course_discount_label'] ?? ''),
            'foundation_discount_label' => $data['foundation_discount_label_ar'] ?? ($data['foundation_discount_label'] ?? ''),
            'total_discount_label' => $data['total_discount_label_ar'] ?? ($data['total_discount_label'] ?? ''),
            'confirm_button_text' => $data['confirm_button_text_ar'] ?? ($data['confirm_button_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Booking summary updated');
    }

    // -------- Articles Page --------
    public function editCourseEnglishArticles(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'articles');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.articles', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'empty' => $content['empty_state'] ?? [],
            'emptyAr' => $arContent['empty_state'] ?? [],
            'card' => $content['card'] ?? [],
            'cardAr' => $arContent['card'] ?? [],
            'sidebar' => $content['sidebar'] ?? [],
            'sidebarAr' => $arContent['sidebar'] ?? [],
        ]);
    }

    public function updateCourseEnglishArticlesHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'articles');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
        ];
        $arContent['hero'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishArticlesEmpty(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'articles');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:500'],
            'message_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['empty_state'] = [
            'heading' => $data['heading'] ?? '',
            'message' => $data['message'] ?? '',
        ];
        $arContent['empty_state'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'message' => $data['message_ar'] ?? ($data['message'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Empty state updated');
    }

    public function updateCourseEnglishArticlesCard(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'articles');

        $data = $request->validate([
            'read_more_label' => ['nullable', 'string', 'max:120'],
            'read_more_label_ar' => ['nullable', 'string', 'max:120'],
            'category_fallback' => ['nullable', 'string', 'max:120'],
            'category_fallback_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['card'] = [
            'read_more_label' => $data['read_more_label'] ?? '',
            'category_fallback' => $data['category_fallback'] ?? '',
        ];
        $arContent['card'] = [
            'read_more_label' => $data['read_more_label_ar'] ?? ($data['read_more_label'] ?? ''),
            'category_fallback' => $data['category_fallback_ar'] ?? ($data['category_fallback'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Card labels updated');
    }

    public function updateCourseEnglishArticlesSidebar(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'articles');

        $data = $request->validate([
            'recent_title' => ['nullable', 'string', 'max:255'],
            'recent_title_ar' => ['nullable', 'string', 'max:255'],
            'recent_badge' => ['nullable', 'string', 'max:120'],
            'recent_badge_ar' => ['nullable', 'string', 'max:120'],
            'categories_title' => ['nullable', 'string', 'max:255'],
            'categories_title_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['sidebar'] = [
            'recent_title' => $data['recent_title'] ?? '',
            'recent_badge' => $data['recent_badge'] ?? '',
            'categories_title' => $data['categories_title'] ?? '',
        ];
        $arContent['sidebar'] = [
            'recent_title' => $data['recent_title_ar'] ?? ($data['recent_title'] ?? ''),
            'recent_badge' => $data['recent_badge_ar'] ?? ($data['recent_badge'] ?? ''),
            'categories_title' => $data['categories_title_ar'] ?? ($data['categories_title'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Sidebar labels updated');
    }

    // -------- Contact Us Page --------
    public function editCourseEnglishContact(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.contact', [
            'page' => $page,
            'content' => $content,
            'arContent' => $arContent,
        ]);
    }

    public function updateCourseEnglishContact(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'breadcrumb.home' => ['nullable', 'string', 'max:120'],
            'breadcrumb.home_ar' => ['nullable', 'string', 'max:120'],
            'breadcrumb.current' => ['nullable', 'string', 'max:120'],
            'breadcrumb.current_ar' => ['nullable', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],

            'cards' => ['nullable', 'array'],
            'cards.*.icon' => ['nullable', 'string', 'max:30'],
            'cards.*.label' => ['nullable', 'string', 'max:255'],
            'cards.*.label_ar' => ['nullable', 'string', 'max:255'],
            'cards.*.value' => ['nullable', 'string', 'max:255'],
            'cards.*.value_ar' => ['nullable', 'string', 'max:255'],
            'cards.*.href' => ['nullable', 'string', 'max:255'],

            'form.title' => ['nullable', 'string', 'max:255'],
            'form.title_ar' => ['nullable', 'string', 'max:255'],
            'form.full_name_label' => ['nullable', 'string', 'max:120'],
            'form.full_name_label_ar' => ['nullable', 'string', 'max:120'],
            'form.full_name_placeholder' => ['nullable', 'string', 'max:255'],
            'form.full_name_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form.email_label' => ['nullable', 'string', 'max:120'],
            'form.email_label_ar' => ['nullable', 'string', 'max:120'],
            'form.email_placeholder' => ['nullable', 'string', 'max:255'],
            'form.email_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form.message_label' => ['nullable', 'string', 'max:120'],
            'form.message_label_ar' => ['nullable', 'string', 'max:120'],
            'form.message_placeholder' => ['nullable', 'string', 'max:255'],
            'form.message_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form.submit_text' => ['nullable', 'string', 'max:120'],
            'form.submit_text_ar' => ['nullable', 'string', 'max:120'],
            'form.sending_text' => ['nullable', 'string', 'max:120'],
            'form.sending_text_ar' => ['nullable', 'string', 'max:120'],
            'form.success_text' => ['nullable', 'string', 'max:255'],
            'form.success_text_ar' => ['nullable', 'string', 'max:255'],
            'form.fail_text' => ['nullable', 'string', 'max:255'],
            'form.fail_text_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $cardsEn = collect($data['cards'] ?? [])->values()->map(function (array $item) {
            return [
                'icon' => $item['icon'] ?? '',
                'label' => $item['label'] ?? '',
                'value' => $item['value'] ?? '',
                'href' => $item['href'] ?? '',
            ];
        })->all();

        $cardsAr = collect($data['cards'] ?? [])->values()->map(function (array $item) {
            return [
                'icon' => $item['icon'] ?? '',
                'label' => $item['label_ar'] ?? ($item['label'] ?? ''),
                'value' => $item['value_ar'] ?? ($item['value'] ?? ''),
                'href' => $item['href'] ?? '',
            ];
        })->all();

        $content = [
            'breadcrumb' => [
                'home' => $data['breadcrumb']['home'] ?? '',
                'current' => $data['breadcrumb']['current'] ?? '',
            ],
            'title' => $data['title'] ?? '',
            'cards' => $cardsEn,
            'form' => [
                'title' => $data['form']['title'] ?? '',
                'full_name_label' => $data['form']['full_name_label'] ?? '',
                'full_name_placeholder' => $data['form']['full_name_placeholder'] ?? '',
                'email_label' => $data['form']['email_label'] ?? '',
                'email_placeholder' => $data['form']['email_placeholder'] ?? '',
                'message_label' => $data['form']['message_label'] ?? '',
                'message_placeholder' => $data['form']['message_placeholder'] ?? '',
                'submit_text' => $data['form']['submit_text'] ?? '',
                'sending_text' => $data['form']['sending_text'] ?? '',
                'success_text' => $data['form']['success_text'] ?? '',
                'fail_text' => $data['form']['fail_text'] ?? '',
            ],
        ];

        $arContent = [
            'breadcrumb' => [
                'home' => $data['breadcrumb']['home_ar'] ?? ($data['breadcrumb']['home'] ?? ''),
                'current' => $data['breadcrumb']['current_ar'] ?? ($data['breadcrumb']['current'] ?? ''),
            ],
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'cards' => $cardsAr,
            'form' => [
                'title' => $data['form']['title_ar'] ?? ($data['form']['title'] ?? ''),
                'full_name_label' => $data['form']['full_name_label_ar'] ?? ($data['form']['full_name_label'] ?? ''),
                'full_name_placeholder' => $data['form']['full_name_placeholder_ar'] ?? ($data['form']['full_name_placeholder'] ?? ''),
                'email_label' => $data['form']['email_label_ar'] ?? ($data['form']['email_label'] ?? ''),
                'email_placeholder' => $data['form']['email_placeholder_ar'] ?? ($data['form']['email_placeholder'] ?? ''),
                'message_label' => $data['form']['message_label_ar'] ?? ($data['form']['message_label'] ?? ''),
                'message_placeholder' => $data['form']['message_placeholder_ar'] ?? ($data['form']['message_placeholder'] ?? ''),
                'submit_text' => $data['form']['submit_text_ar'] ?? ($data['form']['submit_text'] ?? ''),
                'sending_text' => $data['form']['sending_text_ar'] ?? ($data['form']['sending_text'] ?? ''),
                'success_text' => $data['form']['success_text_ar'] ?? ($data['form']['success_text'] ?? ''),
                'fail_text' => $data['form']['fail_text_ar'] ?? ($data['form']['fail_text'] ?? ''),
            ],
        ];

        $this->persistPageContent($page, $content, $arContent);

        return back()->with('success', 'Contact page updated');
    }

    public function updateCourseEnglishContactHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:120'],
            'badge_ar' => ['nullable', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['badge'] ?? '',
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['badge_ar'] ?? ($data['badge'] ?? ''),
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishContactOffices(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'button_text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['offices'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'button_text' => $data['button_text'] ?? '',
        ];
        $arContent['offices'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'button_text' => $data['button_text_ar'] ?? ($data['button_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Offices section updated');
    }

    public function updateCourseEnglishContactInquiries(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'eyebrow_ar' => ['nullable', 'string', 'max:120'],
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['inquiries'] = [
            'eyebrow' => $data['eyebrow'] ?? '',
            'heading' => $data['heading'] ?? '',
            'description' => $data['description'] ?? '',
        ];
        $arContent['inquiries'] = [
            'eyebrow' => $data['eyebrow_ar'] ?? ($data['eyebrow'] ?? ''),
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Inquiries section updated');
    }

    public function updateCourseEnglishContactCards(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'email_title' => ['nullable', 'string', 'max:255'],
            'email_title_ar' => ['nullable', 'string', 'max:255'],
            'email_description' => ['nullable', 'string', 'max:500'],
            'email_description_ar' => ['nullable', 'string', 'max:500'],
            'phone_title' => ['nullable', 'string', 'max:255'],
            'phone_title_ar' => ['nullable', 'string', 'max:255'],
            'phone_description' => ['nullable', 'string', 'max:500'],
            'phone_description_ar' => ['nullable', 'string', 'max:500'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['contact_cards'] = [
            'email_title' => $data['email_title'] ?? '',
            'email_description' => $data['email_description'] ?? '',
            'phone_title' => $data['phone_title'] ?? '',
            'phone_description' => $data['phone_description'] ?? '',
            'whatsapp_number' => $data['whatsapp_number'] ?? '',
        ];
        $arContent['contact_cards'] = [
            'email_title' => $data['email_title_ar'] ?? ($data['email_title'] ?? ''),
            'email_description' => $data['email_description_ar'] ?? ($data['email_description'] ?? ''),
            'phone_title' => $data['phone_title_ar'] ?? ($data['phone_title'] ?? ''),
            'phone_description' => $data['phone_description_ar'] ?? ($data['phone_description'] ?? ''),
            'whatsapp_number' => $data['whatsapp_number'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Contact cards updated');
    }

    public function updateCourseEnglishContactSocial(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['social'] = [
            'heading' => $data['heading'] ?? '',
        ];
        $arContent['social'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Social section updated');
    }

    public function updateCourseEnglishContactForm(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'contact-us');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['form'] = [
            'title' => $data['title'] ?? '',
        ];
        $arContent['form'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Form title updated');
    }

    // -------- Travel & Tourism Page --------
    public function editCourseEnglishTravel(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.travel', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'cta' => $content['cta_card'] ?? [],
            'ctaAr' => $arContent['cta_card'] ?? [],
            'features' => $content['features'] ?? [],
            'featuresAr' => $arContent['features'] ?? [],
            'destinations' => $content['destinations'] ?? [],
            'destinationsAr' => $arContent['destinations'] ?? [],
            'services' => $content['services'] ?? [],
            'servicesAr' => $arContent['services'] ?? [],
            'inquiry' => $content['inquiry'] ?? [],
            'inquiryAr' => $arContent['inquiry'] ?? [],
        ]);
    }

    public function updateCourseEnglishTravelHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');

        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:120'],
            'badge_ar' => ['nullable', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:800'],
            'description_ar' => ['nullable', 'string', 'max:800'],
            'background_image' => ['nullable', 'string', 'max:255'],
            'background_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'primary_cta_text' => ['nullable', 'string', 'max:120'],
            'primary_cta_text_ar' => ['nullable', 'string', 'max:120'],
            'primary_cta_url' => ['nullable', 'string', 'max:255'],
            'secondary_cta_text' => ['nullable', 'string', 'max:120'],
            'secondary_cta_text_ar' => ['nullable', 'string', 'max:120'],
            'secondary_cta_url' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('background_image_file')) {
            $data['background_image'] = $this->storeUpload($request->file('background_image_file'), 'travel-hero-bg');
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['badge'] ?? '',
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'background_image' => $data['background_image'] ?? '',
            'primary_cta_text' => $data['primary_cta_text'] ?? '',
            'primary_cta_url' => $data['primary_cta_url'] ?? '',
            'secondary_cta_text' => $data['secondary_cta_text'] ?? '',
            'secondary_cta_url' => $data['secondary_cta_url'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['badge_ar'] ?? ($data['badge'] ?? ''),
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
            'background_image' => $data['background_image'] ?? '',
            'primary_cta_text' => $data['primary_cta_text_ar'] ?? ($data['primary_cta_text'] ?? ''),
            'primary_cta_url' => $data['primary_cta_url'] ?? '',
            'secondary_cta_text' => $data['secondary_cta_text_ar'] ?? ($data['secondary_cta_text'] ?? ''),
            'secondary_cta_url' => $data['secondary_cta_url'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishTravelCta(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'whatsapp_text' => ['nullable', 'string', 'max:120'],
            'whatsapp_text_ar' => ['nullable', 'string', 'max:120'],
            'whatsapp_url' => ['nullable', 'string', 'max:255'],
            'call_text' => ['nullable', 'string', 'max:120'],
            'call_text_ar' => ['nullable', 'string', 'max:120'],
            'call_url' => ['nullable', 'string', 'max:255'],
            'inquire_text' => ['nullable', 'string', 'max:120'],
            'inquire_text_ar' => ['nullable', 'string', 'max:120'],
            'inquire_url' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['cta_card'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'whatsapp_text' => $data['whatsapp_text'] ?? '',
            'whatsapp_url' => $data['whatsapp_url'] ?? '',
            'call_text' => $data['call_text'] ?? '',
            'call_url' => $data['call_url'] ?? '',
            'inquire_text' => $data['inquire_text'] ?? '',
            'inquire_url' => $data['inquire_url'] ?? '',
        ];
        $arContent['cta_card'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'whatsapp_text' => $data['whatsapp_text_ar'] ?? ($data['whatsapp_text'] ?? ''),
            'whatsapp_url' => $data['whatsapp_url'] ?? '',
            'call_text' => $data['call_text_ar'] ?? ($data['call_text'] ?? ''),
            'call_url' => $data['call_url'] ?? '',
            'inquire_text' => $data['inquire_text_ar'] ?? ($data['inquire_text'] ?? ''),
            'inquire_url' => $data['inquire_url'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'CTA card updated');
    }

    public function updateCourseEnglishTravelFeatures(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');

        $data = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*.icon' => ['nullable', 'string', 'max:120'],
            'items.*.title' => ['nullable', 'string', 'max:255'],
            'items.*.title_ar' => ['nullable', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:500'],
            'items.*.description_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $items = collect($data['items'] ?? [])->map(function ($item) {
            return [
                'icon' => $item['icon'] ?? '',
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
            ];
        })->values()->all();
        $itemsAr = collect($data['items'] ?? [])->map(function ($item) {
            return [
                'icon' => $item['icon'] ?? '',
                'title' => $item['title_ar'] ?? ($item['title'] ?? ''),
                'description' => $item['description_ar'] ?? ($item['description'] ?? ''),
            ];
        })->values()->all();

        $content['features'] = ['items' => $items];
        $arContent['features'] = ['items' => $itemsAr];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Features updated');
    }

    public function updateCourseEnglishTravelDestinations(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');

        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'eyebrow_ar' => ['nullable', 'string', 'max:120'],
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'view_all_text' => ['nullable', 'string', 'max:120'],
            'view_all_text_ar' => ['nullable', 'string', 'max:120'],
            'view_all_url' => ['nullable', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*.name' => ['nullable', 'string', 'max:255'],
            'items.*.name_ar' => ['nullable', 'string', 'max:255'],
            'items.*.image' => ['nullable', 'string', 'max:255'],
            'items.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'items.*.price' => ['nullable', 'string', 'max:120'],
            'items.*.price_ar' => ['nullable', 'string', 'max:120'],
            'items.*.tag' => ['nullable', 'string', 'max:120'],
            'items.*.tag_ar' => ['nullable', 'string', 'max:120'],
            'items.*.url' => ['nullable', 'string', 'max:255'],
            'items.*.url_ar' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data['items'] ?? [] as $idx => $item) {
            if ($request->hasFile("items.$idx.image_file")) {
                $data['items'][$idx]['image'] = $this->storeUpload($request->file("items.$idx.image_file"), 'travel-dest-' . $idx);
            }
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $items = collect($data['items'] ?? [])->map(function ($item) {
            return [
                'name' => $item['name'] ?? '',
                'image' => $item['image'] ?? '',
                'price' => $item['price'] ?? '',
                'tag' => $item['tag'] ?? '',
                'url' => $item['url'] ?? '',
            ];
        })->values()->all();
        $itemsAr = collect($data['items'] ?? [])->map(function ($item) {
            return [
                'name' => $item['name_ar'] ?? ($item['name'] ?? ''),
                'image' => $item['image'] ?? '',
                'price' => $item['price_ar'] ?? ($item['price'] ?? ''),
                'tag' => $item['tag_ar'] ?? ($item['tag'] ?? ''),
                'url' => $item['url_ar'] ?? ($item['url'] ?? ''),
            ];
        })->values()->all();

        $content['destinations'] = [
            'eyebrow' => $data['eyebrow'] ?? '',
            'heading' => $data['heading'] ?? '',
            'view_all_text' => $data['view_all_text'] ?? '',
            'view_all_url' => $data['view_all_url'] ?? '',
            'items' => $items,
        ];
        $arContent['destinations'] = [
            'eyebrow' => $data['eyebrow_ar'] ?? ($data['eyebrow'] ?? ''),
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'view_all_text' => $data['view_all_text_ar'] ?? ($data['view_all_text'] ?? ''),
            'view_all_url' => $data['view_all_url'] ?? '',
            'items' => $itemsAr,
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Destinations updated');
    }

    public function updateCourseEnglishTravelServices(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');

        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'eyebrow_ar' => ['nullable', 'string', 'max:120'],
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:700'],
            'description_ar' => ['nullable', 'string', 'max:700'],
            'items' => ['nullable', 'array'],
            'items.*.icon' => ['nullable', 'string', 'max:120'],
            'items.*.title' => ['nullable', 'string', 'max:255'],
            'items.*.title_ar' => ['nullable', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string', 'max:700'],
            'items.*.description_ar' => ['nullable', 'string', 'max:700'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $items = collect($data['items'] ?? [])->map(function ($item) {
            return [
                'icon' => $item['icon'] ?? '',
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
            ];
        })->values()->all();
        $itemsAr = collect($data['items'] ?? [])->map(function ($item) {
            return [
                'icon' => $item['icon'] ?? '',
                'title' => $item['title_ar'] ?? ($item['title'] ?? ''),
                'description' => $item['description_ar'] ?? ($item['description'] ?? ''),
            ];
        })->values()->all();

        $content['services'] = [
            'eyebrow' => $data['eyebrow'] ?? '',
            'heading' => $data['heading'] ?? '',
            'description' => $data['description'] ?? '',
            'items' => $items,
        ];
        $arContent['services'] = [
            'eyebrow' => $data['eyebrow_ar'] ?? ($data['eyebrow'] ?? ''),
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
            'items' => $itemsAr,
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Services updated');
    }

    public function updateCourseEnglishTravelInquiry(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'travel-and-tourism');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:800'],
            'description_ar' => ['nullable', 'string', 'max:800'],
            'bullets' => ['nullable', 'array'],
            'bullets.*' => ['nullable', 'string', 'max:255'],
            'bullets_ar' => ['nullable', 'array'],
            'bullets_ar.*' => ['nullable', 'string', 'max:255'],
            'whatsapp_text' => ['nullable', 'string', 'max:120'],
            'whatsapp_text_ar' => ['nullable', 'string', 'max:120'],
            'whatsapp_url' => ['nullable', 'string', 'max:255'],
            'form_title' => ['nullable', 'string', 'max:255'],
            'form_title_ar' => ['nullable', 'string', 'max:255'],
            'form_name_label' => ['nullable', 'string', 'max:255'],
            'form_name_label_ar' => ['nullable', 'string', 'max:255'],
            'form_name_placeholder' => ['nullable', 'string', 'max:255'],
            'form_name_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form_phone_label' => ['nullable', 'string', 'max:255'],
            'form_phone_label_ar' => ['nullable', 'string', 'max:255'],
            'form_phone_placeholder' => ['nullable', 'string', 'max:255'],
            'form_phone_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form_destination_label' => ['nullable', 'string', 'max:255'],
            'form_destination_label_ar' => ['nullable', 'string', 'max:255'],
            'form_destination_placeholder' => ['nullable', 'string', 'max:255'],
            'form_destination_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form_date_label' => ['nullable', 'string', 'max:255'],
            'form_date_label_ar' => ['nullable', 'string', 'max:255'],
            'form_message_label' => ['nullable', 'string', 'max:255'],
            'form_message_label_ar' => ['nullable', 'string', 'max:255'],
            'form_message_placeholder' => ['nullable', 'string', 'max:255'],
            'form_message_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'form_submit_text' => ['nullable', 'string', 'max:120'],
            'form_submit_text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['inquiry'] = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'bullets' => array_values(array_filter($data['bullets'] ?? [])),
            'whatsapp_text' => $data['whatsapp_text'] ?? '',
            'whatsapp_url' => $data['whatsapp_url'] ?? '',
            'form_title' => $data['form_title'] ?? '',
            'form_name_label' => $data['form_name_label'] ?? '',
            'form_name_placeholder' => $data['form_name_placeholder'] ?? '',
            'form_phone_label' => $data['form_phone_label'] ?? '',
            'form_phone_placeholder' => $data['form_phone_placeholder'] ?? '',
            'form_destination_label' => $data['form_destination_label'] ?? '',
            'form_destination_placeholder' => $data['form_destination_placeholder'] ?? '',
            'form_date_label' => $data['form_date_label'] ?? '',
            'form_message_label' => $data['form_message_label'] ?? '',
            'form_message_placeholder' => $data['form_message_placeholder'] ?? '',
            'form_submit_text' => $data['form_submit_text'] ?? '',
        ];
        $arContent['inquiry'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
            'bullets' => array_values(array_filter($data['bullets_ar'] ?? ($data['bullets'] ?? []))),
            'whatsapp_text' => $data['whatsapp_text_ar'] ?? ($data['whatsapp_text'] ?? ''),
            'whatsapp_url' => $data['whatsapp_url'] ?? '',
            'form_title' => $data['form_title_ar'] ?? ($data['form_title'] ?? ''),
            'form_name_label' => $data['form_name_label_ar'] ?? ($data['form_name_label'] ?? ''),
            'form_name_placeholder' => $data['form_name_placeholder_ar'] ?? ($data['form_name_placeholder'] ?? ''),
            'form_phone_label' => $data['form_phone_label_ar'] ?? ($data['form_phone_label'] ?? ''),
            'form_phone_placeholder' => $data['form_phone_placeholder_ar'] ?? ($data['form_phone_placeholder'] ?? ''),
            'form_destination_label' => $data['form_destination_label_ar'] ?? ($data['form_destination_label'] ?? ''),
            'form_destination_placeholder' => $data['form_destination_placeholder_ar'] ?? ($data['form_destination_placeholder'] ?? ''),
            'form_date_label' => $data['form_date_label_ar'] ?? ($data['form_date_label'] ?? ''),
            'form_message_label' => $data['form_message_label_ar'] ?? ($data['form_message_label'] ?? ''),
            'form_message_placeholder' => $data['form_message_placeholder_ar'] ?? ($data['form_message_placeholder'] ?? ''),
            'form_submit_text' => $data['form_submit_text_ar'] ?? ($data['form_submit_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Inquiry section updated');
    }

    // -------- University Admissions Page --------
    public function editCourseEnglishUniversityAdmissions(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'university-admissions');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.university-admissions', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'cards' => $content['cards'] ?? [],
            'cardsAr' => $arContent['cards'] ?? [],
            'stats' => $content['stats'] ?? [],
            'statsAr' => $arContent['stats'] ?? [],
        ]);
    }

    public function updateCourseEnglishUniversityAdmissionsHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'university-admissions');

        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:120'],
            'badge_ar' => ['nullable', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['badge'] ?? '',
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['badge_ar'] ?? ($data['badge'] ?? ''),
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'description' => $data['description_ar'] ?? ($data['description'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishUniversityAdmissionsCards(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'university-admissions');

        $data = $request->validate([
            'cards' => ['nullable', 'array'],
            'cards.*.title' => ['nullable', 'string', 'max:255'],
            'cards.*.title_ar' => ['nullable', 'string', 'max:255'],
            'cards.*.description' => ['nullable', 'string', 'max:500'],
            'cards.*.description_ar' => ['nullable', 'string', 'max:500'],
            'cards.*.button_text' => ['nullable', 'string', 'max:120'],
            'cards.*.button_text_ar' => ['nullable', 'string', 'max:120'],
            'cards.*.url' => ['nullable', 'string', 'max:255'],
            'cards.*.image' => ['nullable', 'string', 'max:255'],
            'cards.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'cards.*.icon' => ['nullable', 'string', 'max:120'],
        ]);

        foreach ($data['cards'] ?? [] as $idx => $card) {
            if ($request->hasFile("cards.$idx.image_file")) {
                $data['cards'][$idx]['image'] = $this->storeUpload($request->file("cards.$idx.image_file"), 'ua-card-' . $idx);
            }
        }

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $cards = collect($data['cards'] ?? [])->map(function ($card) {
            return [
                'title' => $card['title'] ?? '',
                'description' => $card['description'] ?? '',
                'button_text' => $card['button_text'] ?? '',
                'url' => $card['url'] ?? '',
                'image' => $card['image'] ?? '',
                'icon' => $card['icon'] ?? '',
            ];
        })->values()->all();
        $cardsAr = collect($data['cards'] ?? [])->map(function ($card) {
            return [
                'title' => $card['title_ar'] ?? ($card['title'] ?? ''),
                'description' => $card['description_ar'] ?? ($card['description'] ?? ''),
                'button_text' => $card['button_text_ar'] ?? ($card['button_text'] ?? ''),
                'url' => $card['url'] ?? '',
                'image' => $card['image'] ?? '',
                'icon' => $card['icon'] ?? '',
            ];
        })->values()->all();

        $content['cards'] = $cards;
        $arContent['cards'] = $cardsAr;

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Cards updated');
    }

    public function updateCourseEnglishUniversityAdmissionsStats(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'university-admissions');

        $data = $request->validate([
            'stats' => ['nullable', 'array'],
            'stats.*.value' => ['nullable', 'string', 'max:50'],
            'stats.*.label' => ['nullable', 'string', 'max:255'],
            'stats.*.label_ar' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $stats = collect($data['stats'] ?? [])->map(function ($item) {
            return [
                'value' => $item['value'] ?? '',
                'label' => $item['label'] ?? '',
            ];
        })->values()->all();
        $statsAr = collect($data['stats'] ?? [])->map(function ($item) {
            return [
                'value' => $item['value'] ?? '',
                'label' => $item['label_ar'] ?? ($item['label'] ?? ''),
            ];
        })->values()->all();

        $content['stats'] = $stats;
        $arContent['stats'] = $statsAr;

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Stats updated');
    }

    // -------- Compare Page --------
    public function editCourseEnglishCompare(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'compare');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.compare', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'table' => $content['table'] ?? [],
            'tableAr' => $arContent['table'] ?? [],
        ]);
    }

    public function updateCourseEnglishCompareHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'compare');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'subtitle_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'title' => $data['title'] ?? '',
            'subtitle' => $data['subtitle'] ?? '',
        ];
        $arContent['hero'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'subtitle' => $data['subtitle_ar'] ?? ($data['subtitle'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishCompareTable(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'compare');

        $data = $request->validate([
            'criteria_label' => ['nullable', 'string', 'max:120'],
            'criteria_label_ar' => ['nullable', 'string', 'max:120'],
            'price_label' => ['nullable', 'string', 'max:120'],
            'price_label_ar' => ['nullable', 'string', 'max:120'],
            'action_button_text' => ['nullable', 'string', 'max:120'],
            'action_button_text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['table'] = [
            'criteria_label' => $data['criteria_label'] ?? '',
            'price_label' => $data['price_label'] ?? '',
            'action_button_text' => $data['action_button_text'] ?? '',
        ];
        $arContent['table'] = [
            'criteria_label' => $data['criteria_label_ar'] ?? ($data['criteria_label'] ?? ''),
            'price_label' => $data['price_label_ar'] ?? ($data['price_label'] ?? ''),
            'action_button_text' => $data['action_button_text_ar'] ?? ($data['action_button_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Table labels updated');
    }

    // -------- Wishlist Page --------
    public function editCourseEnglishWishlist(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'wishlist');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.wishlist', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'card' => $content['card'] ?? [],
            'cardAr' => $arContent['card'] ?? [],
            'empty' => $content['empty_state'] ?? [],
            'emptyAr' => $arContent['empty_state'] ?? [],
        ]);
    }

    public function updateCourseEnglishWishlistHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'wishlist');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'subtitle_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'title' => $data['title'] ?? '',
            'subtitle' => $data['subtitle'] ?? '',
        ];
        $arContent['hero'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'subtitle' => $data['subtitle_ar'] ?? ($data['subtitle'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishWishlistCard(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'wishlist');

        $data = $request->validate([
            'type_suffix' => ['nullable', 'string', 'max:120'],
            'type_suffix_ar' => ['nullable', 'string', 'max:120'],
            'view_details_text' => ['nullable', 'string', 'max:120'],
            'view_details_text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['card'] = [
            'type_suffix' => $data['type_suffix'] ?? '',
            'view_details_text' => $data['view_details_text'] ?? '',
        ];
        $arContent['card'] = [
            'type_suffix' => $data['type_suffix_ar'] ?? ($data['type_suffix'] ?? ''),
            'view_details_text' => $data['view_details_text_ar'] ?? ($data['view_details_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Card labels updated');
    }

    public function updateCourseEnglishWishlistEmpty(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'wishlist');

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'subtitle_ar' => ['nullable', 'string', 'max:500'],
            'cta_text' => ['nullable', 'string', 'max:120'],
            'cta_text_ar' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:255'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['empty_state'] = [
            'title' => $data['title'] ?? '',
            'subtitle' => $data['subtitle'] ?? '',
            'cta_text' => $data['cta_text'] ?? '',
            'cta_url' => $data['cta_url'] ?? '',
        ];
        $arContent['empty_state'] = [
            'title' => $data['title_ar'] ?? ($data['title'] ?? ''),
            'subtitle' => $data['subtitle_ar'] ?? ($data['subtitle'] ?? ''),
            'cta_text' => $data['cta_text_ar'] ?? ($data['cta_text'] ?? ''),
            'cta_url' => $data['cta_url'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Empty state updated');
    }

    // -------- Online Courses Page --------
    public function editCourseEnglishOnlineCourses(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'online-courses');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.online-courses', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'results' => $content['results'] ?? [],
            'resultsAr' => $arContent['results'] ?? [],
            'loadMore' => $content['load_more'] ?? [],
            'loadMoreAr' => $arContent['load_more'] ?? [],
        ]);
    }

    public function updateCourseEnglishOnlineCoursesHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'online-courses');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'level_label' => ['nullable', 'string', 'max:120'],
            'level_label_ar' => ['nullable', 'string', 'max:120'],
            'level_placeholder' => ['nullable', 'string', 'max:255'],
            'level_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'level_options' => ['nullable', 'array'],
            'level_options.*' => ['nullable', 'string', 'max:120'],
            'level_options_ar' => ['nullable', 'array'],
            'level_options_ar.*' => ['nullable', 'string', 'max:120'],
            'focus_label' => ['nullable', 'string', 'max:120'],
            'focus_label_ar' => ['nullable', 'string', 'max:120'],
            'focus_placeholder' => ['nullable', 'string', 'max:255'],
            'focus_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'focus_options' => ['nullable', 'array'],
            'focus_options.*' => ['nullable', 'string', 'max:120'],
            'focus_options_ar' => ['nullable', 'array'],
            'focus_options_ar.*' => ['nullable', 'string', 'max:120'],
            'schedule_label' => ['nullable', 'string', 'max:120'],
            'schedule_label_ar' => ['nullable', 'string', 'max:120'],
            'schedule_placeholder' => ['nullable', 'string', 'max:255'],
            'schedule_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'schedule_options' => ['nullable', 'array'],
            'schedule_options.*' => ['nullable', 'string', 'max:120'],
            'schedule_options_ar' => ['nullable', 'array'],
            'schedule_options_ar.*' => ['nullable', 'string', 'max:120'],
            'start_label' => ['nullable', 'string', 'max:120'],
            'start_label_ar' => ['nullable', 'string', 'max:120'],
            'start_placeholder' => ['nullable', 'string', 'max:255'],
            'start_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'search_aria' => ['nullable', 'string', 'max:120'],
            'search_aria_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'level_label' => $data['level_label'] ?? '',
            'level_placeholder' => $data['level_placeholder'] ?? '',
            'level_options' => array_values(array_filter($data['level_options'] ?? [])),
            'focus_label' => $data['focus_label'] ?? '',
            'focus_placeholder' => $data['focus_placeholder'] ?? '',
            'focus_options' => array_values(array_filter($data['focus_options'] ?? [])),
            'schedule_label' => $data['schedule_label'] ?? '',
            'schedule_placeholder' => $data['schedule_placeholder'] ?? '',
            'schedule_options' => array_values(array_filter($data['schedule_options'] ?? [])),
            'start_label' => $data['start_label'] ?? '',
            'start_placeholder' => $data['start_placeholder'] ?? '',
            'search_aria' => $data['search_aria'] ?? '',
        ];
        $arContent['hero'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'level_label' => $data['level_label_ar'] ?? ($data['level_label'] ?? ''),
            'level_placeholder' => $data['level_placeholder_ar'] ?? ($data['level_placeholder'] ?? ''),
            'level_options' => array_values(array_filter($data['level_options_ar'] ?? ($data['level_options'] ?? []))),
            'focus_label' => $data['focus_label_ar'] ?? ($data['focus_label'] ?? ''),
            'focus_placeholder' => $data['focus_placeholder_ar'] ?? ($data['focus_placeholder'] ?? ''),
            'focus_options' => array_values(array_filter($data['focus_options_ar'] ?? ($data['focus_options'] ?? []))),
            'schedule_label' => $data['schedule_label_ar'] ?? ($data['schedule_label'] ?? ''),
            'schedule_placeholder' => $data['schedule_placeholder_ar'] ?? ($data['schedule_placeholder'] ?? ''),
            'schedule_options' => array_values(array_filter($data['schedule_options_ar'] ?? ($data['schedule_options'] ?? []))),
            'start_label' => $data['start_label_ar'] ?? ($data['start_label'] ?? ''),
            'start_placeholder' => $data['start_placeholder_ar'] ?? ($data['start_placeholder'] ?? ''),
            'search_aria' => $data['search_aria_ar'] ?? ($data['search_aria'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishOnlineCoursesResults(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'online-courses');

        $data = $request->validate([
            'count_label' => ['nullable', 'string', 'max:255'],
            'count_label_ar' => ['nullable', 'string', 'max:255'],
            'step_label' => ['nullable', 'string', 'max:255'],
            'step_label_ar' => ['nullable', 'string', 'max:255'],
            'sort_label' => ['nullable', 'string', 'max:120'],
            'sort_label_ar' => ['nullable', 'string', 'max:120'],
            'sort_options' => ['nullable', 'array'],
            'sort_options.*' => ['nullable', 'string', 'max:120'],
            'sort_options_ar' => ['nullable', 'array'],
            'sort_options_ar.*' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['results'] = [
            'count_label' => $data['count_label'] ?? '',
            'step_label' => $data['step_label'] ?? '',
            'sort_label' => $data['sort_label'] ?? '',
            'sort_options' => array_values(array_filter($data['sort_options'] ?? [])),
        ];
        $arContent['results'] = [
            'count_label' => $data['count_label_ar'] ?? ($data['count_label'] ?? ''),
            'step_label' => $data['step_label_ar'] ?? ($data['step_label'] ?? ''),
            'sort_label' => $data['sort_label_ar'] ?? ($data['sort_label'] ?? ''),
            'sort_options' => array_values(array_filter($data['sort_options_ar'] ?? ($data['sort_options'] ?? []))),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Results header updated');
    }

    public function updateCourseEnglishOnlineCoursesLoadMore(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'online-courses');

        $data = $request->validate([
            'text' => ['nullable', 'string', 'max:120'],
            'text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['load_more'] = [
            'text' => $data['text'] ?? '',
        ];
        $arContent['load_more'] = [
            'text' => $data['text_ar'] ?? ($data['text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Load more updated');
    }

    // -------- Summer Programs Page --------
    public function editCourseEnglishSummerPrograms(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'summer-programs');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.summer-programs', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'results' => $content['results'] ?? [],
            'resultsAr' => $arContent['results'] ?? [],
            'loadMore' => $content['load_more'] ?? [],
            'loadMoreAr' => $arContent['load_more'] ?? [],
        ]);
    }

    public function updateCourseEnglishSummerProgramsHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'summer-programs');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'destination_label' => ['nullable', 'string', 'max:120'],
            'destination_label_ar' => ['nullable', 'string', 'max:120'],
            'destination_placeholder' => ['nullable', 'string', 'max:255'],
            'destination_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'age_label' => ['nullable', 'string', 'max:120'],
            'age_label_ar' => ['nullable', 'string', 'max:120'],
            'age_placeholder' => ['nullable', 'string', 'max:255'],
            'age_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'age_options' => ['nullable', 'array'],
            'age_options.*' => ['nullable', 'string', 'max:120'],
            'age_options_ar' => ['nullable', 'array'],
            'age_options_ar.*' => ['nullable', 'string', 'max:120'],
            'duration_label' => ['nullable', 'string', 'max:120'],
            'duration_label_ar' => ['nullable', 'string', 'max:120'],
            'duration_placeholder' => ['nullable', 'string', 'max:255'],
            'duration_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'duration_options' => ['nullable', 'array'],
            'duration_options.*' => ['nullable', 'string', 'max:120'],
            'duration_options_ar' => ['nullable', 'array'],
            'duration_options_ar.*' => ['nullable', 'string', 'max:120'],
            'start_label' => ['nullable', 'string', 'max:120'],
            'start_label_ar' => ['nullable', 'string', 'max:120'],
            'start_placeholder' => ['nullable', 'string', 'max:255'],
            'start_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'search_aria' => ['nullable', 'string', 'max:120'],
            'search_aria_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'destination_label' => $data['destination_label'] ?? '',
            'destination_placeholder' => $data['destination_placeholder'] ?? '',
            'age_label' => $data['age_label'] ?? '',
            'age_placeholder' => $data['age_placeholder'] ?? '',
            'age_options' => array_values(array_filter($data['age_options'] ?? [])),
            'duration_label' => $data['duration_label'] ?? '',
            'duration_placeholder' => $data['duration_placeholder'] ?? '',
            'duration_options' => array_values(array_filter($data['duration_options'] ?? [])),
            'start_label' => $data['start_label'] ?? '',
            'start_placeholder' => $data['start_placeholder'] ?? '',
            'search_aria' => $data['search_aria'] ?? '',
        ];
        $arContent['hero'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'destination_label' => $data['destination_label_ar'] ?? ($data['destination_label'] ?? ''),
            'destination_placeholder' => $data['destination_placeholder_ar'] ?? ($data['destination_placeholder'] ?? ''),
            'age_label' => $data['age_label_ar'] ?? ($data['age_label'] ?? ''),
            'age_placeholder' => $data['age_placeholder_ar'] ?? ($data['age_placeholder'] ?? ''),
            'age_options' => array_values(array_filter($data['age_options_ar'] ?? ($data['age_options'] ?? []))),
            'duration_label' => $data['duration_label_ar'] ?? ($data['duration_label'] ?? ''),
            'duration_placeholder' => $data['duration_placeholder_ar'] ?? ($data['duration_placeholder'] ?? ''),
            'duration_options' => array_values(array_filter($data['duration_options_ar'] ?? ($data['duration_options'] ?? []))),
            'start_label' => $data['start_label_ar'] ?? ($data['start_label'] ?? ''),
            'start_placeholder' => $data['start_placeholder_ar'] ?? ($data['start_placeholder'] ?? ''),
            'search_aria' => $data['search_aria_ar'] ?? ($data['search_aria'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishSummerProgramsResults(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'summer-programs');

        $data = $request->validate([
            'count_label' => ['nullable', 'string', 'max:255'],
            'count_label_ar' => ['nullable', 'string', 'max:255'],
            'step_label' => ['nullable', 'string', 'max:255'],
            'step_label_ar' => ['nullable', 'string', 'max:255'],
            'sort_label' => ['nullable', 'string', 'max:120'],
            'sort_label_ar' => ['nullable', 'string', 'max:120'],
            'sort_options' => ['nullable', 'array'],
            'sort_options.*' => ['nullable', 'string', 'max:120'],
            'sort_options_ar' => ['nullable', 'array'],
            'sort_options_ar.*' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['results'] = [
            'count_label' => $data['count_label'] ?? '',
            'step_label' => $data['step_label'] ?? '',
            'sort_label' => $data['sort_label'] ?? '',
            'sort_options' => array_values(array_filter($data['sort_options'] ?? [])),
        ];
        $arContent['results'] = [
            'count_label' => $data['count_label_ar'] ?? ($data['count_label'] ?? ''),
            'step_label' => $data['step_label_ar'] ?? ($data['step_label'] ?? ''),
            'sort_label' => $data['sort_label_ar'] ?? ($data['sort_label'] ?? ''),
            'sort_options' => array_values(array_filter($data['sort_options_ar'] ?? ($data['sort_options'] ?? []))),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Results header updated');
    }

    public function updateCourseEnglishSummerProgramsLoadMore(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'summer-programs');

        $data = $request->validate([
            'text' => ['nullable', 'string', 'max:120'],
            'text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['load_more'] = [
            'text' => $data['text'] ?? '',
        ];
        $arContent['load_more'] = [
            'text' => $data['text_ar'] ?? ($data['text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Load more updated');
    }

    // -------- Training & Professional Courses Page --------
    public function editCourseEnglishTrainingCourses(): View
    {
        $page = $this->getPageBySlug('courseenglish', 'training-and-professional-courses');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.courseenglish.training-courses', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'results' => $content['results'] ?? [],
            'resultsAr' => $arContent['results'] ?? [],
            'loadMore' => $content['load_more'] ?? [],
            'loadMoreAr' => $arContent['load_more'] ?? [],
        ]);
    }

    public function updateCourseEnglishTrainingCoursesHero(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'training-and-professional-courses');

        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'subheading_ar' => ['nullable', 'string', 'max:500'],
            'subject_label' => ['nullable', 'string', 'max:120'],
            'subject_label_ar' => ['nullable', 'string', 'max:120'],
            'subject_placeholder' => ['nullable', 'string', 'max:255'],
            'subject_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'subject_options' => ['nullable', 'array'],
            'subject_options.*' => ['nullable', 'string', 'max:120'],
            'subject_options_ar' => ['nullable', 'array'],
            'subject_options_ar.*' => ['nullable', 'string', 'max:120'],
            'location_label' => ['nullable', 'string', 'max:120'],
            'location_label_ar' => ['nullable', 'string', 'max:120'],
            'location_placeholder' => ['nullable', 'string', 'max:255'],
            'location_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'duration_label' => ['nullable', 'string', 'max:120'],
            'duration_label_ar' => ['nullable', 'string', 'max:120'],
            'duration_placeholder' => ['nullable', 'string', 'max:255'],
            'duration_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'duration_options' => ['nullable', 'array'],
            'duration_options.*' => ['nullable', 'string', 'max:120'],
            'duration_options_ar' => ['nullable', 'array'],
            'duration_options_ar.*' => ['nullable', 'string', 'max:120'],
            'start_label' => ['nullable', 'string', 'max:120'],
            'start_label_ar' => ['nullable', 'string', 'max:120'],
            'start_placeholder' => ['nullable', 'string', 'max:255'],
            'start_placeholder_ar' => ['nullable', 'string', 'max:255'],
            'search_aria' => ['nullable', 'string', 'max:120'],
            'search_aria_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'heading' => $data['heading'] ?? '',
            'subheading' => $data['subheading'] ?? '',
            'subject_label' => $data['subject_label'] ?? '',
            'subject_placeholder' => $data['subject_placeholder'] ?? '',
            'subject_options' => array_values(array_filter($data['subject_options'] ?? [])),
            'location_label' => $data['location_label'] ?? '',
            'location_placeholder' => $data['location_placeholder'] ?? '',
            'duration_label' => $data['duration_label'] ?? '',
            'duration_placeholder' => $data['duration_placeholder'] ?? '',
            'duration_options' => array_values(array_filter($data['duration_options'] ?? [])),
            'start_label' => $data['start_label'] ?? '',
            'start_placeholder' => $data['start_placeholder'] ?? '',
            'search_aria' => $data['search_aria'] ?? '',
        ];
        $arContent['hero'] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
            'subheading' => $data['subheading_ar'] ?? ($data['subheading'] ?? ''),
            'subject_label' => $data['subject_label_ar'] ?? ($data['subject_label'] ?? ''),
            'subject_placeholder' => $data['subject_placeholder_ar'] ?? ($data['subject_placeholder'] ?? ''),
            'subject_options' => array_values(array_filter($data['subject_options_ar'] ?? ($data['subject_options'] ?? []))),
            'location_label' => $data['location_label_ar'] ?? ($data['location_label'] ?? ''),
            'location_placeholder' => $data['location_placeholder_ar'] ?? ($data['location_placeholder'] ?? ''),
            'duration_label' => $data['duration_label_ar'] ?? ($data['duration_label'] ?? ''),
            'duration_placeholder' => $data['duration_placeholder_ar'] ?? ($data['duration_placeholder'] ?? ''),
            'duration_options' => array_values(array_filter($data['duration_options_ar'] ?? ($data['duration_options'] ?? []))),
            'start_label' => $data['start_label_ar'] ?? ($data['start_label'] ?? ''),
            'start_placeholder' => $data['start_placeholder_ar'] ?? ($data['start_placeholder'] ?? ''),
            'search_aria' => $data['search_aria_ar'] ?? ($data['search_aria'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Hero updated');
    }

    public function updateCourseEnglishTrainingCoursesResults(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'training-and-professional-courses');

        $data = $request->validate([
            'count_label' => ['nullable', 'string', 'max:255'],
            'count_label_ar' => ['nullable', 'string', 'max:255'],
            'step_label' => ['nullable', 'string', 'max:255'],
            'step_label_ar' => ['nullable', 'string', 'max:255'],
            'sort_label' => ['nullable', 'string', 'max:120'],
            'sort_label_ar' => ['nullable', 'string', 'max:120'],
            'sort_options' => ['nullable', 'array'],
            'sort_options.*' => ['nullable', 'string', 'max:120'],
            'sort_options_ar' => ['nullable', 'array'],
            'sort_options_ar.*' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['results'] = [
            'count_label' => $data['count_label'] ?? '',
            'step_label' => $data['step_label'] ?? '',
            'sort_label' => $data['sort_label'] ?? '',
            'sort_options' => array_values(array_filter($data['sort_options'] ?? [])),
        ];
        $arContent['results'] = [
            'count_label' => $data['count_label_ar'] ?? ($data['count_label'] ?? ''),
            'step_label' => $data['step_label_ar'] ?? ($data['step_label'] ?? ''),
            'sort_label' => $data['sort_label_ar'] ?? ($data['sort_label'] ?? ''),
            'sort_options' => array_values(array_filter($data['sort_options_ar'] ?? ($data['sort_options'] ?? []))),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Results header updated');
    }

    public function updateCourseEnglishTrainingCoursesLoadMore(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'training-and-professional-courses');

        $data = $request->validate([
            'text' => ['nullable', 'string', 'max:120'],
            'text_ar' => ['nullable', 'string', 'max:120'],
        ]);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['load_more'] = [
            'text' => $data['text'] ?? '',
        ];
        $arContent['load_more'] = [
            'text' => $data['text_ar'] ?? ($data['text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Load more updated');
    }

    // ---------------- University CMS ----------------
    public function editUniversityHome(): View
    {
        $page = $this->getPageBySlug('university', 'home');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.home', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'stats' => $content['stats'] ?? [],
            'statsAr' => $arContent['stats'] ?? [],
            'certificates' => $content['certificates'] ?? [],
            'certificatesAr' => $arContent['certificates'] ?? [],
            'destinations' => $content['destinations'] ?? [],
            'destinationsAr' => $arContent['destinations'] ?? [],
            'universities' => $content['universities'] ?? [],
            'universitiesAr' => $arContent['universities'] ?? [],
            'reviews' => $content['reviews'] ?? [],
            'reviewsAr' => $arContent['reviews'] ?? [],
            'scholarships' => $content['scholarships'] ?? [],
            'scholarshipsAr' => $arContent['scholarships'] ?? [],
            'trust' => $content['trust'] ?? [],
            'trustAr' => $arContent['trust'] ?? [],
            'faq' => $content['faq'] ?? [],
            'faqAr' => $arContent['faq'] ?? [],
            'blogs' => $content['blogs'] ?? [],
            'blogsAr' => $arContent['blogs'] ?? [],
        ]);
    }

    public function updateUniversityHome(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'home');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'headline' => $data['hero']['headline'] ?? '',
            'subheadline' => $data['hero']['subheadline'] ?? '',
            'background_image' => $data['hero']['background_image'] ?? '',
            'figure_image' => $data['hero']['figure_image'] ?? '',
            'search_label' => $data['hero']['search_label'] ?? '',
            'search_placeholder_courses' => $data['hero']['search_placeholder_courses'] ?? '',
            'search_placeholder_universities' => $data['hero']['search_placeholder_universities'] ?? '',
            'country_label' => $data['hero']['country_label'] ?? '',
            'country_placeholder' => $data['hero']['country_placeholder'] ?? '',
            'level_label' => $data['hero']['level_label'] ?? '',
            'level_placeholder' => $data['hero']['level_placeholder'] ?? '',
            'intake_label' => $data['hero']['intake_label'] ?? '',
            'intake_placeholder' => $data['hero']['intake_placeholder'] ?? '',
            'tab_courses' => $data['hero']['tab_courses'] ?? '',
            'tab_universities' => $data['hero']['tab_universities'] ?? '',
            'search_button_text' => $data['hero']['search_button_text'] ?? '',
        ];
        $arContent['hero'] = [
            'headline' => $data['hero']['headline_ar'] ?? ($data['hero']['headline'] ?? ''),
            'subheadline' => $data['hero']['subheadline_ar'] ?? ($data['hero']['subheadline'] ?? ''),
            'background_image' => $data['hero']['background_image'] ?? '',
            'figure_image' => $data['hero']['figure_image'] ?? '',
            'search_label' => $data['hero']['search_label_ar'] ?? ($data['hero']['search_label'] ?? ''),
            'search_placeholder_courses' => $data['hero']['search_placeholder_courses_ar'] ?? ($data['hero']['search_placeholder_courses'] ?? ''),
            'search_placeholder_universities' => $data['hero']['search_placeholder_universities_ar'] ?? ($data['hero']['search_placeholder_universities'] ?? ''),
            'country_label' => $data['hero']['country_label_ar'] ?? ($data['hero']['country_label'] ?? ''),
            'country_placeholder' => $data['hero']['country_placeholder_ar'] ?? ($data['hero']['country_placeholder'] ?? ''),
            'level_label' => $data['hero']['level_label_ar'] ?? ($data['hero']['level_label'] ?? ''),
            'level_placeholder' => $data['hero']['level_placeholder_ar'] ?? ($data['hero']['level_placeholder'] ?? ''),
            'intake_label' => $data['hero']['intake_label_ar'] ?? ($data['hero']['intake_label'] ?? ''),
            'intake_placeholder' => $data['hero']['intake_placeholder_ar'] ?? ($data['hero']['intake_placeholder'] ?? ''),
            'tab_courses' => $data['hero']['tab_courses_ar'] ?? ($data['hero']['tab_courses'] ?? ''),
            'tab_universities' => $data['hero']['tab_universities_ar'] ?? ($data['hero']['tab_universities'] ?? ''),
            'search_button_text' => $data['hero']['search_button_text_ar'] ?? ($data['hero']['search_button_text'] ?? ''),
        ];

        $statsItems = $data['stats']['items'] ?? [];
        $content['stats'] = [
            'heading' => $data['stats']['heading'] ?? '',
            'body' => $data['stats']['body'] ?? '',
            'mobile_heading' => $data['stats']['mobile_heading'] ?? '',
            'items' => collect($statsItems)->map(fn($item) => [
                'value' => $item['value'] ?? '',
                'label' => $item['label'] ?? '',
            ])->values()->all(),
        ];
        $arContent['stats'] = [
            'heading' => $data['stats']['heading_ar'] ?? ($data['stats']['heading'] ?? ''),
            'body' => $data['stats']['body_ar'] ?? ($data['stats']['body'] ?? ''),
            'mobile_heading' => $data['stats']['mobile_heading_ar'] ?? ($data['stats']['mobile_heading'] ?? ''),
            'items' => collect($statsItems)->map(fn($item) => [
                'value' => $item['value'] ?? '',
                'label' => $item['label_ar'] ?? ($item['label'] ?? ''),
            ])->values()->all(),
        ];

        $content['certificates'] = [
            'heading' => $data['certificates']['heading'] ?? '',
            'body' => $data['certificates']['body'] ?? '',
        ];
        $arContent['certificates'] = [
            'heading' => $data['certificates']['heading_ar'] ?? ($data['certificates']['heading'] ?? ''),
            'body' => $data['certificates']['body_ar'] ?? ($data['certificates']['body'] ?? ''),
        ];

        $content['destinations'] = [
            'title' => $data['destinations']['title'] ?? '',
            'subtitle' => $data['destinations']['subtitle'] ?? '',
            'view_all' => $data['destinations']['view_all'] ?? '',
        ];
        $arContent['destinations'] = [
            'title' => $data['destinations']['title_ar'] ?? ($data['destinations']['title'] ?? ''),
            'subtitle' => $data['destinations']['subtitle_ar'] ?? ($data['destinations']['subtitle'] ?? ''),
            'view_all' => $data['destinations']['view_all_ar'] ?? ($data['destinations']['view_all'] ?? ''),
        ];

        $content['universities'] = [
            'title' => $data['universities']['title'] ?? '',
            'subtitle' => $data['universities']['subtitle'] ?? '',
            'view_all' => $data['universities']['view_all'] ?? '',
            'browse_all' => $data['universities']['browse_all'] ?? '',
        ];
        $arContent['universities'] = [
            'title' => $data['universities']['title_ar'] ?? ($data['universities']['title'] ?? ''),
            'subtitle' => $data['universities']['subtitle_ar'] ?? ($data['universities']['subtitle'] ?? ''),
            'view_all' => $data['universities']['view_all_ar'] ?? ($data['universities']['view_all'] ?? ''),
            'browse_all' => $data['universities']['browse_all_ar'] ?? ($data['universities']['browse_all'] ?? ''),
        ];

        $content['reviews'] = [
            'video_title' => $data['reviews']['video_title'] ?? '',
            'video_subtitle' => $data['reviews']['video_subtitle'] ?? '',
            'text_title' => $data['reviews']['text_title'] ?? '',
            'text_subtitle' => $data['reviews']['text_subtitle'] ?? '',
        ];
        $arContent['reviews'] = [
            'video_title' => $data['reviews']['video_title_ar'] ?? ($data['reviews']['video_title'] ?? ''),
            'video_subtitle' => $data['reviews']['video_subtitle_ar'] ?? ($data['reviews']['video_subtitle'] ?? ''),
            'text_title' => $data['reviews']['text_title_ar'] ?? ($data['reviews']['text_title'] ?? ''),
            'text_subtitle' => $data['reviews']['text_subtitle_ar'] ?? ($data['reviews']['text_subtitle'] ?? ''),
        ];

        $content['scholarships'] = [
            'title' => $data['scholarships']['title'] ?? '',
            'subtitle' => $data['scholarships']['subtitle'] ?? '',
            'view_all' => $data['scholarships']['view_all'] ?? '',
            'check_eligibility' => $data['scholarships']['check_eligibility'] ?? '',
        ];
        $arContent['scholarships'] = [
            'title' => $data['scholarships']['title_ar'] ?? ($data['scholarships']['title'] ?? ''),
            'subtitle' => $data['scholarships']['subtitle_ar'] ?? ($data['scholarships']['subtitle'] ?? ''),
            'view_all' => $data['scholarships']['view_all_ar'] ?? ($data['scholarships']['view_all'] ?? ''),
            'check_eligibility' => $data['scholarships']['check_eligibility_ar'] ?? ($data['scholarships']['check_eligibility'] ?? ''),
        ];

        $trustItems = $data['trust']['items'] ?? [];
        $content['trust'] = [
            'title' => $data['trust']['title'] ?? '',
            'subtitle' => $data['trust']['subtitle'] ?? '',
            'items' => collect($trustItems)->map(fn($item) => [
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'icon' => $item['icon'] ?? '',
            ])->values()->all(),
        ];
        $arContent['trust'] = [
            'title' => $data['trust']['title_ar'] ?? ($data['trust']['title'] ?? ''),
            'subtitle' => $data['trust']['subtitle_ar'] ?? ($data['trust']['subtitle'] ?? ''),
            'items' => collect($trustItems)->map(fn($item) => [
                'title' => $item['title_ar'] ?? ($item['title'] ?? ''),
                'description' => $item['description_ar'] ?? ($item['description'] ?? ''),
                'icon' => $item['icon'] ?? '',
            ])->values()->all(),
        ];

        $content['faq'] = [
            'heading' => $data['faq']['heading'] ?? '',
            'subheading' => $data['faq']['subheading'] ?? '',
            'cta_title' => $data['faq']['cta_title'] ?? '',
            'cta_text' => $data['faq']['cta_text'] ?? '',
            'show_more' => $data['faq']['show_more'] ?? '',
        ];
        $arContent['faq'] = [
            'heading' => $data['faq']['heading_ar'] ?? ($data['faq']['heading'] ?? ''),
            'subheading' => $data['faq']['subheading_ar'] ?? ($data['faq']['subheading'] ?? ''),
            'cta_title' => $data['faq']['cta_title_ar'] ?? ($data['faq']['cta_title'] ?? ''),
            'cta_text' => $data['faq']['cta_text_ar'] ?? ($data['faq']['cta_text'] ?? ''),
            'show_more' => $data['faq']['show_more_ar'] ?? ($data['faq']['show_more'] ?? ''),
        ];

        $content['blogs'] = [
            'heading' => $data['blogs']['heading'] ?? '',
            'heading_mobile' => $data['blogs']['heading_mobile'] ?? '',
            'cta_text' => $data['blogs']['cta_text'] ?? '',
            'cta_mobile' => $data['blogs']['cta_mobile'] ?? '',
        ];
        $arContent['blogs'] = [
            'heading' => $data['blogs']['heading_ar'] ?? ($data['blogs']['heading'] ?? ''),
            'heading_mobile' => $data['blogs']['heading_mobile_ar'] ?? ($data['blogs']['heading_mobile'] ?? ''),
            'cta_text' => $data['blogs']['cta_text_ar'] ?? ($data['blogs']['cta_text'] ?? ''),
            'cta_mobile' => $data['blogs']['cta_mobile_ar'] ?? ($data['blogs']['cta_mobile'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'University home page updated');
    }

    public function editUniversityAbout(): View
    {
        $page = $this->getPageBySlug('university', 'about');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.about', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'director' => $content['director_message'] ?? [],
            'directorAr' => $arContent['director_message'] ?? [],
            'ceo' => $content['ceo_message'] ?? [],
            'ceoAr' => $arContent['ceo_message'] ?? [],
            'teamMeta' => $content['team'] ?? [],
            'teamMetaAr' => $arContent['team'] ?? [],
            'team' => $content['team']['members'] ?? [],
            'teamAr' => $arContent['team']['members'] ?? [],
        ]);
    }

    public function updateUniversityAbout(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'about');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'image' => $data['hero']['image'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'image' => $data['hero']['image'] ?? '',
        ];

        $content['director_message'] = [
            'title' => $data['director']['title'] ?? '',
            'name' => $data['director']['name'] ?? '',
            'role' => $data['director']['role'] ?? '',
            'paragraphs' => $this->explodeParagraphs($data['director']['message'] ?? ''),
            'closing' => [
                'text' => $data['director']['closing_text'] ?? '',
                'name' => $data['director']['closing_name'] ?? '',
                'position' => $data['director']['closing_position'] ?? '',
            ],
            'image' => $data['director']['image'] ?? ($content['director_message']['image'] ?? ''),
        ];
        $arContent['director_message'] = [
            'title' => $data['director_ar']['title'] ?? ($data['director']['title'] ?? ''),
            'name' => $data['director_ar']['name'] ?? ($data['director']['name'] ?? ''),
            'role' => $data['director_ar']['role'] ?? ($data['director']['role'] ?? ''),
            'paragraphs' => $this->explodeParagraphs($data['director_ar']['message'] ?? ($data['director']['message'] ?? '')),
            'closing' => [
                'text' => $data['director_ar']['closing_text'] ?? ($data['director']['closing_text'] ?? ''),
                'name' => $data['director_ar']['closing_name'] ?? ($data['director']['closing_name'] ?? ''),
                'position' => $data['director_ar']['closing_position'] ?? ($data['director']['closing_position'] ?? ''),
            ],
            'image' => $data['director']['image'] ?? ($content['director_message']['image'] ?? ''),
        ];

        $content['ceo_message'] = [
            'title' => $data['ceo']['title'] ?? '',
            'name' => $data['ceo']['name'] ?? '',
            'role' => $data['ceo']['role'] ?? '',
            'paragraphs' => $this->explodeParagraphs($data['ceo']['message'] ?? ''),
            'closing' => [
                'text' => $data['ceo']['closing_text'] ?? '',
                'name' => $data['ceo']['closing_name'] ?? '',
                'position' => $data['ceo']['closing_position'] ?? '',
            ],
            'image' => $data['ceo']['image'] ?? ($content['ceo_message']['image'] ?? ''),
        ];
        $arContent['ceo_message'] = [
            'title' => $data['ceo_ar']['title'] ?? ($data['ceo']['title'] ?? ''),
            'name' => $data['ceo_ar']['name'] ?? ($data['ceo']['name'] ?? ''),
            'role' => $data['ceo_ar']['role'] ?? ($data['ceo']['role'] ?? ''),
            'paragraphs' => $this->explodeParagraphs($data['ceo_ar']['message'] ?? ($data['ceo']['message'] ?? '')),
            'closing' => [
                'text' => $data['ceo_ar']['closing_text'] ?? ($data['ceo']['closing_text'] ?? ''),
                'name' => $data['ceo_ar']['closing_name'] ?? ($data['ceo']['closing_name'] ?? ''),
                'position' => $data['ceo_ar']['closing_position'] ?? ($data['ceo']['closing_position'] ?? ''),
            ],
            'image' => $data['ceo']['image'] ?? ($content['ceo_message']['image'] ?? ''),
        ];

        $content['team'] = [
            'badge' => $data['team']['badge'] ?? '',
            'title' => $data['team']['title'] ?? '',
            'members' => $this->mapTeam($data['team']['members'] ?? []),
        ];
        $arContent['team'] = [
            'badge' => $data['team']['badge_ar'] ?? ($data['team']['badge'] ?? ''),
            'title' => $data['team']['title_ar'] ?? ($data['team']['title'] ?? ''),
            'members' => $this->mapTeam($data['team']['members'] ?? [], true),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'About page updated');
    }

    public function editUniversityContact(): View
    {
        $page = $this->getPageBySlug('university', 'contact');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.contact', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'contactInfo' => $content['contact_info'] ?? [],
            'contactInfoAr' => $arContent['contact_info'] ?? [],
            'offices' => $content['offices'] ?? [],
            'officesAr' => $arContent['offices'] ?? [],
        ]);
    }

    public function updateUniversityContact(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'contact');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
        ];

        [$infoItems, $infoItemsAr] = $this->splitItems($data['contact_info']['items'] ?? [], ['title', 'value', 'icon'], [
            'title' => 'title_ar',
            'value' => 'value_ar',
            'icon' => 'icon',
        ]);
        $content['contact_info'] = [
            'title' => $data['contact_info']['title'] ?? '',
            'description' => $data['contact_info']['description'] ?? '',
            'items' => $infoItems,
        ];
        $arContent['contact_info'] = [
            'title' => $data['contact_info']['title_ar'] ?? ($data['contact_info']['title'] ?? ''),
            'description' => $data['contact_info']['description_ar'] ?? ($data['contact_info']['description'] ?? ''),
            'items' => $infoItemsAr,
        ];

        [$officeItems, $officeItemsAr] = $this->splitItems($data['offices']['items'] ?? [], ['city', 'address', 'phone', 'email'], [
            'city' => 'city_ar',
            'address' => 'address_ar',
            'phone' => 'phone',
            'email' => 'email',
        ]);
        $content['offices'] = [
            'title' => $data['offices']['title'] ?? '',
            'description' => $data['offices']['description'] ?? '',
            'items' => $officeItems,
        ];
        $arContent['offices'] = [
            'title' => $data['offices']['title_ar'] ?? ($data['offices']['title'] ?? ''),
            'description' => $data['offices']['description_ar'] ?? ($data['offices']['description'] ?? ''),
            'items' => $officeItemsAr,
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Contact page updated');
    }

    public function editUniversityServices(): View
    {
        $page = $this->getPageBySlug('university', 'services');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.services', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'offer' => $content['what_we_offer'] ?? [],
            'offerAr' => $arContent['what_we_offer'] ?? [],
            'process' => $content['our_process'] ?? [],
            'processAr' => $arContent['our_process'] ?? [],
            'partner' => $content['partner_section'] ?? [],
            'partnerAr' => $arContent['partner_section'] ?? [],
            'cta' => $content['cta'] ?? [],
            'ctaAr' => $arContent['cta'] ?? [],
        ]);
    }

    public function updateUniversityServices(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'services');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'image' => $data['hero']['image'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'image' => $data['hero']['image'] ?? '',
        ];

        [$offerItems, $offerItemsAr] = $this->splitItems($data['what_we_offer']['items'] ?? [], ['title', 'description', 'icon', 'link'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
            'icon' => 'icon',
            'link' => 'link',
        ]);
        $content['what_we_offer'] = [
            'title' => $data['what_we_offer']['title'] ?? '',
            'description' => $data['what_we_offer']['description'] ?? '',
            'items' => $offerItems,
        ];
        $arContent['what_we_offer'] = [
            'title' => $data['what_we_offer']['title_ar'] ?? ($data['what_we_offer']['title'] ?? ''),
            'description' => $data['what_we_offer']['description_ar'] ?? ($data['what_we_offer']['description'] ?? ''),
            'items' => $offerItemsAr,
        ];

        [$processSteps, $processStepsAr] = $this->splitItems($data['our_process']['steps'] ?? [], ['number', 'title', 'description'], [
            'number' => 'number',
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['our_process'] = [
            'title' => $data['our_process']['title'] ?? '',
            'description' => $data['our_process']['description'] ?? '',
            'steps' => $processSteps,
        ];
        $arContent['our_process'] = [
            'title' => $data['our_process']['title_ar'] ?? ($data['our_process']['title'] ?? ''),
            'description' => $data['our_process']['description_ar'] ?? ($data['our_process']['description'] ?? ''),
            'steps' => $processStepsAr,
        ];

        $content['partner_section'] = [
            'badge' => $data['partner_section']['badge'] ?? '',
            'title' => $data['partner_section']['title'] ?? '',
            'description' => $data['partner_section']['description'] ?? '',
            'button_text' => $data['partner_section']['button_text'] ?? '',
            'button_link' => $data['partner_section']['button_link'] ?? '',
            'secondary_button_text' => $data['partner_section']['secondary_button_text'] ?? '',
            'secondary_button_link' => $data['partner_section']['secondary_button_link'] ?? '',
        ];
        $arContent['partner_section'] = [
            'badge' => $data['partner_section']['badge_ar'] ?? ($data['partner_section']['badge'] ?? ''),
            'title' => $data['partner_section']['title_ar'] ?? ($data['partner_section']['title'] ?? ''),
            'description' => $data['partner_section']['description_ar'] ?? ($data['partner_section']['description'] ?? ''),
            'button_text' => $data['partner_section']['button_text_ar'] ?? ($data['partner_section']['button_text'] ?? ''),
            'button_link' => $data['partner_section']['button_link'] ?? '',
            'secondary_button_text' => $data['partner_section']['secondary_button_text_ar'] ?? ($data['partner_section']['secondary_button_text'] ?? ''),
            'secondary_button_link' => $data['partner_section']['secondary_button_link'] ?? '',
        ];

        $content['cta'] = [
            'title' => $data['cta']['title'] ?? '',
            'description' => $data['cta']['description'] ?? '',
            'button_text' => $data['cta']['button_text'] ?? '',
            'button_link' => $data['cta']['button_link'] ?? '',
        ];
        $arContent['cta'] = [
            'title' => $data['cta']['title_ar'] ?? ($data['cta']['title'] ?? ''),
            'description' => $data['cta']['description_ar'] ?? ($data['cta']['description'] ?? ''),
            'button_text' => $data['cta']['button_text_ar'] ?? ($data['cta']['button_text'] ?? ''),
            'button_link' => $data['cta']['button_link'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Services page updated');
    }

    public function editUniversityVisa(): View
    {
        $page = $this->getPageBySlug('university', 'visa-support');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.visa', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'services' => $content['services_section'] ?? [],
            'servicesAr' => $arContent['services_section'] ?? [],
            'process' => $content['process_section'] ?? [],
            'processAr' => $arContent['process_section'] ?? [],
            'cta' => $content['cta_section'] ?? [],
            'ctaAr' => $arContent['cta_section'] ?? [],
        ]);
    }

    public function updateUniversityVisa(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'visa-support');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
        ];

        [$serviceItems, $serviceItemsAr] = $this->splitItems($data['services_section']['items'] ?? [], ['title', 'description', 'icon'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
            'icon' => 'icon',
        ]);
        $content['services_section'] = [
            'subtitle' => $data['services_section']['subtitle'] ?? '',
            'title' => $data['services_section']['title'] ?? '',
            'description' => $data['services_section']['description'] ?? '',
            'items' => $serviceItems,
        ];
        $arContent['services_section'] = [
            'subtitle' => $data['services_section']['subtitle_ar'] ?? ($data['services_section']['subtitle'] ?? ''),
            'title' => $data['services_section']['title_ar'] ?? ($data['services_section']['title'] ?? ''),
            'description' => $data['services_section']['description_ar'] ?? ($data['services_section']['description'] ?? ''),
            'items' => $serviceItemsAr,
        ];

        [$steps, $stepsAr] = $this->splitItems($data['process_section']['steps'] ?? [], ['number', 'title', 'description'], [
            'number' => 'number',
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['process_section'] = [
            'subtitle' => $data['process_section']['subtitle'] ?? '',
            'title' => $data['process_section']['title'] ?? '',
            'description' => $data['process_section']['description'] ?? '',
            'steps' => $steps,
        ];
        $arContent['process_section'] = [
            'subtitle' => $data['process_section']['subtitle_ar'] ?? ($data['process_section']['subtitle'] ?? ''),
            'title' => $data['process_section']['title_ar'] ?? ($data['process_section']['title'] ?? ''),
            'description' => $data['process_section']['description_ar'] ?? ($data['process_section']['description'] ?? ''),
            'steps' => $stepsAr,
        ];

        $content['cta_section'] = [
            'title' => $data['cta_section']['title'] ?? '',
            'description' => $data['cta_section']['description'] ?? '',
            'button_text' => $data['cta_section']['button_text'] ?? '',
            'button_link' => $data['cta_section']['button_link'] ?? '',
        ];
        $arContent['cta_section'] = [
            'title' => $data['cta_section']['title_ar'] ?? ($data['cta_section']['title'] ?? ''),
            'description' => $data['cta_section']['description_ar'] ?? ($data['cta_section']['description'] ?? ''),
            'button_text' => $data['cta_section']['button_text_ar'] ?? ($data['cta_section']['button_text'] ?? ''),
            'button_link' => $data['cta_section']['button_link'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Visa Support page updated');
    }

    public function editUniversityAgents(): View
    {
        $page = $this->getPageBySlug('university', 'agents');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.agents', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'stats' => $content['stats'] ?? [],
            'statsAr' => $arContent['stats'] ?? [],
            'services' => $content['services_section'] ?? [],
            'servicesAr' => $arContent['services_section'] ?? [],
            'process' => $content['process_section'] ?? [],
            'processAr' => $arContent['process_section'] ?? [],
            'cta' => $content['cta_section'] ?? [],
            'ctaAr' => $arContent['cta_section'] ?? [],
        ]);
    }

    public function updateUniversityAgents(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'agents');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'cta_primary' => $data['hero']['cta_primary'] ?? '',
            'cta_primary_link' => $data['hero']['cta_primary_link'] ?? '',
            'cta_secondary' => $data['hero']['cta_secondary'] ?? '',
            'cta_secondary_link' => $data['hero']['cta_secondary_link'] ?? '',
            'bg_image' => $data['hero']['bg_image'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'cta_primary' => $data['hero']['cta_primary_ar'] ?? ($data['hero']['cta_primary'] ?? ''),
            'cta_primary_link' => $data['hero']['cta_primary_link'] ?? '',
            'cta_secondary' => $data['hero']['cta_secondary_ar'] ?? ($data['hero']['cta_secondary'] ?? ''),
            'cta_secondary_link' => $data['hero']['cta_secondary_link'] ?? '',
            'bg_image' => $data['hero']['bg_image'] ?? '',
        ];

        [$stats, $statsAr] = $this->splitItems($data['stats'] ?? [], ['value', 'label'], [
            'value' => 'value',
            'label' => 'label_ar',
        ]);
        $content['stats'] = $stats;
        $arContent['stats'] = $statsAr;

        [$serviceItems, $serviceItemsAr] = $this->splitItems($data['services_section']['items'] ?? [], ['title', 'description', 'icon'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
            'icon' => 'icon',
        ]);
        $content['services_section'] = [
            'subtitle' => $data['services_section']['subtitle'] ?? '',
            'title' => $data['services_section']['title'] ?? '',
            'description' => $data['services_section']['description'] ?? '',
            'items' => $serviceItems,
        ];
        $arContent['services_section'] = [
            'subtitle' => $data['services_section']['subtitle_ar'] ?? ($data['services_section']['subtitle'] ?? ''),
            'title' => $data['services_section']['title_ar'] ?? ($data['services_section']['title'] ?? ''),
            'description' => $data['services_section']['description_ar'] ?? ($data['services_section']['description'] ?? ''),
            'items' => $serviceItemsAr,
        ];

        [$steps, $stepsAr] = $this->splitItems($data['process_section']['steps'] ?? [], ['number', 'title', 'description'], [
            'number' => 'number',
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['process_section'] = [
            'title' => $data['process_section']['title'] ?? '',
            'description' => $data['process_section']['description'] ?? '',
            'steps' => $steps,
        ];
        $arContent['process_section'] = [
            'title' => $data['process_section']['title_ar'] ?? ($data['process_section']['title'] ?? ''),
            'description' => $data['process_section']['description_ar'] ?? ($data['process_section']['description'] ?? ''),
            'steps' => $stepsAr,
        ];

        $content['cta_section'] = [
            'title' => $data['cta_section']['title'] ?? '',
            'description' => $data['cta_section']['description'] ?? '',
            'button_text' => $data['cta_section']['button_text'] ?? '',
        ];
        $arContent['cta_section'] = [
            'title' => $data['cta_section']['title_ar'] ?? ($data['cta_section']['title'] ?? ''),
            'description' => $data['cta_section']['description_ar'] ?? ($data['cta_section']['description'] ?? ''),
            'button_text' => $data['cta_section']['button_text_ar'] ?? ($data['cta_section']['button_text'] ?? ''),
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Agents page updated');
    }

    public function editUniversityAccommodation(): View
    {
        $page = $this->getPageBySlug('university', 'accommodation');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.accommodation', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'why' => $content['why_choose_us'] ?? [],
            'whyAr' => $arContent['why_choose_us'] ?? [],
            'process' => $content['booking_process'] ?? [],
            'processAr' => $arContent['booking_process'] ?? [],
            'cta' => $content['cta'] ?? [],
            'ctaAr' => $arContent['cta'] ?? [],
        ]);
    }

    public function updateUniversityAccommodation(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'accommodation');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'image' => $data['hero']['image'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'image' => $data['hero']['image'] ?? '',
        ];

        [$whyItems, $whyItemsAr] = $this->splitItems($data['why_choose_us']['items'] ?? [], ['title', 'description'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['why_choose_us'] = [
            'title' => $data['why_choose_us']['title'] ?? '',
            'items' => $whyItems,
        ];
        $arContent['why_choose_us'] = [
            'title' => $data['why_choose_us']['title_ar'] ?? ($data['why_choose_us']['title'] ?? ''),
            'items' => $whyItemsAr,
        ];

        [$processSteps, $processStepsAr] = $this->splitItems($data['booking_process']['steps'] ?? [], ['number', 'title', 'description'], [
            'number' => 'number',
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['booking_process'] = [
            'title' => $data['booking_process']['title'] ?? '',
            'steps' => $processSteps,
        ];
        $arContent['booking_process'] = [
            'title' => $data['booking_process']['title_ar'] ?? ($data['booking_process']['title'] ?? ''),
            'steps' => $processStepsAr,
        ];

        $content['cta'] = [
            'title' => $data['cta']['title'] ?? '',
            'description' => $data['cta']['description'] ?? '',
            'button_text' => $data['cta']['button_text'] ?? '',
            'button_link' => $data['cta']['button_link'] ?? '',
        ];
        $arContent['cta'] = [
            'title' => $data['cta']['title_ar'] ?? ($data['cta']['title'] ?? ''),
            'description' => $data['cta']['description_ar'] ?? ($data['cta']['description'] ?? ''),
            'button_text' => $data['cta']['button_text_ar'] ?? ($data['cta']['button_text'] ?? ''),
            'button_link' => $data['cta']['button_link'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Accommodation page updated');
    }

    public function editUniversityApplications(): View
    {
        $page = $this->getPageBySlug('university', 'applications');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.applications', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'requirements' => $content['requirements'] ?? [],
            'requirementsAr' => $arContent['requirements'] ?? [],
            'process' => $content['process_steps'] ?? [],
            'processAr' => $arContent['process_steps'] ?? [],
            'cta' => $content['cta'] ?? [],
            'ctaAr' => $arContent['cta'] ?? [],
        ]);
    }

    public function updateUniversityApplications(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'applications');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'image' => $data['hero']['image'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'image' => $data['hero']['image'] ?? '',
        ];

        [$reqItems, $reqItemsAr] = $this->splitItems($data['requirements']['items'] ?? [], ['title', 'description'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['requirements'] = [
            'title' => $data['requirements']['title'] ?? '',
            'items' => $reqItems,
        ];
        $arContent['requirements'] = [
            'title' => $data['requirements']['title_ar'] ?? ($data['requirements']['title'] ?? ''),
            'items' => $reqItemsAr,
        ];

        [$steps, $stepsAr] = $this->splitItems($data['process_steps']['steps'] ?? [], ['number', 'title', 'description'], [
            'number' => 'number',
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['process_steps'] = [
            'title' => $data['process_steps']['title'] ?? '',
            'steps' => $steps,
        ];
        $arContent['process_steps'] = [
            'title' => $data['process_steps']['title_ar'] ?? ($data['process_steps']['title'] ?? ''),
            'steps' => $stepsAr,
        ];

        $content['cta'] = [
            'title' => $data['cta']['title'] ?? '',
            'description' => $data['cta']['description'] ?? '',
            'button_text' => $data['cta']['button_text'] ?? '',
            'button_link' => $data['cta']['button_link'] ?? '',
        ];
        $arContent['cta'] = [
            'title' => $data['cta']['title_ar'] ?? ($data['cta']['title'] ?? ''),
            'description' => $data['cta']['description_ar'] ?? ($data['cta']['description'] ?? ''),
            'button_text' => $data['cta']['button_text_ar'] ?? ($data['cta']['button_text'] ?? ''),
            'button_link' => $data['cta']['button_link'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Applications page updated');
    }

    public function editUniversityApplication(): View
    {
        $page = $this->getPageBySlug('university', 'application');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.application', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'services' => $content['services'] ?? [],
            'servicesAr' => $arContent['services'] ?? [],
            'process' => $content['process'] ?? [],
            'processAr' => $arContent['process'] ?? [],
            'cta' => $content['cta'] ?? [],
            'ctaAr' => $arContent['cta'] ?? [],
        ]);
    }

    public function updateUniversityApplication(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'application');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'btn1_text' => $data['hero']['btn1_text'] ?? '',
            'btn1_link' => $data['hero']['btn1_link'] ?? '',
            'btn2_text' => $data['hero']['btn2_text'] ?? '',
            'btn2_link' => $data['hero']['btn2_link'] ?? '',
        ];
        $arContent['hero'] = [
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'btn1_text' => $data['hero']['btn1_text_ar'] ?? ($data['hero']['btn1_text'] ?? ''),
            'btn1_link' => $data['hero']['btn1_link'] ?? '',
            'btn2_text' => $data['hero']['btn2_text_ar'] ?? ($data['hero']['btn2_text'] ?? ''),
            'btn2_link' => $data['hero']['btn2_link'] ?? '',
        ];

        [$serviceItems, $serviceItemsAr] = $this->splitItems($data['services']['items'] ?? [], ['title', 'description', 'icon'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
            'icon' => 'icon',
        ]);
        $content['services'] = [
            'subtitle' => $data['services']['subtitle'] ?? '',
            'title' => $data['services']['title'] ?? '',
            'description' => $data['services']['description'] ?? '',
            'items' => $serviceItems,
        ];
        $arContent['services'] = [
            'subtitle' => $data['services']['subtitle_ar'] ?? ($data['services']['subtitle'] ?? ''),
            'title' => $data['services']['title_ar'] ?? ($data['services']['title'] ?? ''),
            'description' => $data['services']['description_ar'] ?? ($data['services']['description'] ?? ''),
            'items' => $serviceItemsAr,
        ];

        [$steps, $stepsAr] = $this->splitItems($data['process']['steps'] ?? [], ['number', 'title', 'description'], [
            'number' => 'number',
            'title' => 'title_ar',
            'description' => 'description_ar',
        ]);
        $content['process'] = [
            'subtitle' => $data['process']['subtitle'] ?? '',
            'title' => $data['process']['title'] ?? '',
            'description' => $data['process']['description'] ?? '',
            'steps' => $steps,
            'stat' => [
                'value' => $data['process']['stat_value'] ?? '',
                'label' => $data['process']['stat_label'] ?? '',
                'description' => $data['process']['stat_description'] ?? '',
            ],
        ];
        $arContent['process'] = [
            'subtitle' => $data['process']['subtitle_ar'] ?? ($data['process']['subtitle'] ?? ''),
            'title' => $data['process']['title_ar'] ?? ($data['process']['title'] ?? ''),
            'description' => $data['process']['description_ar'] ?? ($data['process']['description'] ?? ''),
            'steps' => $stepsAr,
            'stat' => [
                'value' => $data['process']['stat_value'] ?? '',
                'label' => $data['process']['stat_label_ar'] ?? ($data['process']['stat_label'] ?? ''),
                'description' => $data['process']['stat_description_ar'] ?? ($data['process']['stat_description'] ?? ''),
            ],
        ];

        $content['cta'] = [
            'title' => $data['cta']['title'] ?? '',
            'description' => $data['cta']['description'] ?? '',
            'btn_text' => $data['cta']['btn_text'] ?? '',
            'btn_link' => $data['cta']['btn_link'] ?? '',
        ];
        $arContent['cta'] = [
            'title' => $data['cta']['title_ar'] ?? ($data['cta']['title'] ?? ''),
            'description' => $data['cta']['description_ar'] ?? ($data['cta']['description'] ?? ''),
            'btn_text' => $data['cta']['btn_text_ar'] ?? ($data['cta']['btn_text'] ?? ''),
            'btn_link' => $data['cta']['btn_link'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Application page updated');
    }

    public function editUniversityStudentGuide(): View
    {
        $page = $this->getPageBySlug('university', 'student-guide');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.student-guide', [
            'page' => $page,
            'hero' => $content['hero'] ?? [],
            'heroAr' => $arContent['hero'] ?? [],
            'categories' => $content['categories'] ?? [],
            'categoriesAr' => $arContent['categories'] ?? [],
            'trust' => $content['trust_section'] ?? [],
            'trustAr' => $arContent['trust_section'] ?? [],
            'tools' => $content['tools_resources'] ?? [],
            'toolsAr' => $arContent['tools_resources'] ?? [],
            'faq' => $content['faq'] ?? [],
            'faqAr' => $arContent['faq'] ?? [],
            'featuredSlug' => $content['featured_guides_category_slug'] ?? '',
            'featuredSlugAr' => $arContent['featured_guides_category_slug'] ?? '',
        ]);
    }

    public function updateUniversityStudentGuide(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'student-guide');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content['hero'] = [
            'badge' => $data['hero']['badge'] ?? '',
            'title' => $data['hero']['title'] ?? '',
            'description' => $data['hero']['description'] ?? '',
            'image' => $data['hero']['image'] ?? '',
        ];
        $arContent['hero'] = [
            'badge' => $data['hero']['badge_ar'] ?? ($data['hero']['badge'] ?? ''),
            'title' => $data['hero']['title_ar'] ?? ($data['hero']['title'] ?? ''),
            'description' => $data['hero']['description_ar'] ?? ($data['hero']['description'] ?? ''),
            'image' => $data['hero']['image'] ?? '',
        ];

        [$cats, $catsAr] = $this->splitItems($data['categories'] ?? [], ['title', 'description', 'icon', 'color'], [
            'title' => 'title_ar',
            'description' => 'description_ar',
            'icon' => 'icon',
            'color' => 'color',
        ]);
        $content['categories'] = $cats;
        $arContent['categories'] = $catsAr;

        $content['trust_section'] = [
            'title' => $data['trust']['title'] ?? '',
            'description' => $data['trust']['description'] ?? '',
            'cta_text' => $data['trust']['cta_text'] ?? '',
            'cta_link' => $data['trust']['cta_link'] ?? '',
        ];
        $arContent['trust_section'] = [
            'title' => $data['trust']['title_ar'] ?? ($data['trust']['title'] ?? ''),
            'description' => $data['trust']['description_ar'] ?? ($data['trust']['description'] ?? ''),
            'cta_text' => $data['trust']['cta_text_ar'] ?? ($data['trust']['cta_text'] ?? ''),
            'cta_link' => $data['trust']['cta_link'] ?? '',
        ];

        [$toolItems, $toolItemsAr] = $this->splitItems($data['tools_resources']['items'] ?? [], ['title', 'type', 'size', 'year', 'destination', 'icon', 'color', 'bg', 'link'], [
            'title' => 'title_ar',
            'type' => 'type_ar',
            'size' => 'size',
            'year' => 'year',
            'destination' => 'destination_ar',
            'icon' => 'icon',
            'color' => 'color',
            'bg' => 'bg',
            'link' => 'link',
        ]);
        $content['tools_resources'] = [
            'title' => $data['tools_resources']['title'] ?? '',
            'subtitle' => $data['tools_resources']['subtitle'] ?? '',
            'description' => $data['tools_resources']['description'] ?? '',
            'items' => $toolItems,
        ];
        $arContent['tools_resources'] = [
            'title' => $data['tools_resources']['title_ar'] ?? ($data['tools_resources']['title'] ?? ''),
            'subtitle' => $data['tools_resources']['subtitle_ar'] ?? ($data['tools_resources']['subtitle'] ?? ''),
            'description' => $data['tools_resources']['description_ar'] ?? ($data['tools_resources']['description'] ?? ''),
            'items' => $toolItemsAr,
        ];

        [$faqItems, $faqItemsAr] = $this->splitItems($data['faq']['items'] ?? [], ['question', 'answer'], [
            'question' => 'question_ar',
            'answer' => 'answer_ar',
        ]);
        $content['faq'] = [
            'title' => $data['faq']['title'] ?? '',
            'subtitle' => $data['faq']['subtitle'] ?? '',
            'description' => $data['faq']['description'] ?? '',
            'items' => $faqItems,
            'cta' => [
                'title' => $data['faq']['cta_title'] ?? '',
                'description' => $data['faq']['cta_description'] ?? '',
                'btn_text' => $data['faq']['cta_btn_text'] ?? '',
                'btn_link' => $data['faq']['cta_btn_link'] ?? '',
            ],
        ];
        $arContent['faq'] = [
            'title' => $data['faq']['title_ar'] ?? ($data['faq']['title'] ?? ''),
            'subtitle' => $data['faq']['subtitle_ar'] ?? ($data['faq']['subtitle'] ?? ''),
            'description' => $data['faq']['description_ar'] ?? ($data['faq']['description'] ?? ''),
            'items' => $faqItemsAr,
            'cta' => [
                'title' => $data['faq']['cta_title_ar'] ?? ($data['faq']['cta_title'] ?? ''),
                'description' => $data['faq']['cta_description_ar'] ?? ($data['faq']['cta_description'] ?? ''),
                'btn_text' => $data['faq']['cta_btn_text_ar'] ?? ($data['faq']['cta_btn_text'] ?? ''),
                'btn_link' => $data['faq']['cta_btn_link'] ?? '',
            ],
        ];

        $content['featured_guides_category_slug'] = $data['featured_guides_category_slug'] ?? '';
        $arContent['featured_guides_category_slug'] = $data['featured_guides_category_slug_ar'] ?? ($data['featured_guides_category_slug'] ?? '');

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Student Guide page updated');
    }

    // ─── University: Destinations ──────────────────────────────────────────

    public function editUniversityDestinations(): View
    {
        $page = $this->getPageBySlug('university', 'destinations');
        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        return view('admin.cms.university.destinations', [
            'page' => $page,
            'content' => $content,
            'arContent' => $arContent,
            'cta' => $content['cta'] ?? [],
            'ctaAr' => $arContent['cta'] ?? [],
        ]);
    }

    public function updateUniversityDestinations(Request $request): RedirectResponse
    {
        $page = $this->getPageBySlug('university', 'destinations');
        $data = $request->all();

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        // Flat text fields (hero + filters + UI labels)
        $flatFields = [
            'hero_title',
            'hero_subtitle',
            'filter_all',
            'filter_europe',
            'filter_na',
            'filter_oceania',
            'filter_budget',
            'search_placeholder',
            'no_match_title',
            'no_match_text',
            'reset_filters',
            'showing',
            'destinations_word',
        ];

        foreach ($flatFields as $field) {
            $content[$field] = $data[$field] ?? ($content[$field] ?? '');
            $arContent[$field] = $data[$field . '_ar'] ?? ($arContent[$field] ?? $content[$field]);
        }

        // CTA section
        $content['cta'] = [
            'title' => $data['cta']['title'] ?? '',
            'description' => $data['cta']['description'] ?? '',
            'button_text' => $data['cta']['button_text'] ?? '',
            'button_link' => $data['cta']['button_link'] ?? '',
        ];
        $arContent['cta'] = [
            'title' => $data['cta']['title_ar'] ?? ($data['cta']['title'] ?? ''),
            'description' => $data['cta']['description_ar'] ?? ($data['cta']['description'] ?? ''),
            'button_text' => $data['cta']['button_text_ar'] ?? ($data['cta']['button_text'] ?? ''),
            'button_link' => $data['cta']['button_link'] ?? '',
        ];

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', 'Destinations page updated');
    }



    private function indexByApp(string $app, string $title): View
    {
        $pages = CmsPage::forApp($app)
            ->orderBy('display_order')
            ->orderBy('title')
            ->get();

        return view('admin.cms.index', [
            'title' => $title,
            'app' => $app,
            'pages' => $pages,
        ]);
    }

    private function decodeContent(?string $json): array
    {
        return json_decode($json ?? '{}', true) ?: [];
    }

    private function persistPageContent(CmsPage $page, array $content, array $arContent): void
    {
        $page->update([
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'ar_content' => json_encode($arContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    private function getPageBySlug(string $app, string $slug): CmsPage
    {
        return CmsPage::where('app', $app)->where('slug', $slug)->firstOrFail();
    }

    private function filterLinksSimple(array $items): array
    {
        return collect($items)->filter(fn($i) => ($i['label'] ?? '') !== '')
            ->map(fn($i) => [
                'label' => $i['label'] ?? '',
                'url' => $i['url'] ?? '',
            ])->values()->all();
    }

    private function updateHeadingCtaSection(Request $request, string $key, string $message, bool $hasCta = false): RedirectResponse
    {
        $page = $this->getPageBySlug('courseenglish', 'home');
        $rules = [
            'heading' => ['nullable', 'string', 'max:255'],
            'heading_ar' => ['nullable', 'string', 'max:255'],
        ];
        if ($hasCta) {
            $rules['cta_text'] = ['nullable', 'string', 'max:255'];
            $rules['cta_text_ar'] = ['nullable', 'string', 'max:255'];
            $rules['cta_url'] = ['nullable', 'string', 'max:255'];
        }
        $data = $request->validate($rules);

        $content = $this->decodeContent($page->content);
        $arContent = $this->decodeContent($page->ar_content);

        $content[$key] = [
            'heading' => $data['heading'] ?? '',
        ];
        $arContent[$key] = [
            'heading' => $data['heading_ar'] ?? ($data['heading'] ?? ''),
        ];
        if ($hasCta) {
            $content[$key]['cta_text'] = $data['cta_text'] ?? '';
            $content[$key]['cta_url'] = $data['cta_url'] ?? '';
            $arContent[$key]['cta_text'] = $data['cta_text_ar'] ?? ($data['cta_text'] ?? '');
            $arContent[$key]['cta_url'] = $data['cta_url'] ?? '';
        }

        $this->persistPageContent($page, $content, $arContent);
        return back()->with('success', $message);
    }

    private function splitItems(array $items, array $fields, array $arMap = []): array
    {
        $contentItems = [];
        $arItems = [];

        foreach ($items as $item) {
            $row = [];
            $rowAr = [];
            $hasValue = false;

            foreach ($fields as $field) {
                $value = $item[$field] ?? '';
                $row[$field] = $value;
                if (trim((string) $value) !== '') {
                    $hasValue = true;
                }
            }

            foreach ($arMap as $field => $source) {
                $value = $item[$source] ?? '';
                $rowAr[$field] = $value;
                if (trim((string) $value) !== '') {
                    $hasValue = true;
                }
            }

            if ($hasValue) {
                $contentItems[] = $row;
                $arItems[] = $rowAr ?: $row;
            }
        }

        return [$contentItems, $arItems];
    }

    private function storeUpload($file, string $name): string
    {
        $safe = Str::slug($name) . '-' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('cms-home', $safe, 'public');
    }

    private function explodeParagraphs(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn($p) => trim($p))
            ->filter()
            ->values()
            ->all();
    }

    private function mapTeam(array $members, bool $useAr = false): array
    {
        return collect($members)->filter(fn($m) => ($m['name'] ?? '') !== '' || ($m['role'] ?? '') !== '')
            ->map(function ($m) use ($useAr) {
                return [
                    'name' => $useAr ? ($m['name_ar'] ?? ($m['name'] ?? '')) : ($m['name'] ?? ''),
                    'role' => $useAr ? ($m['role_ar'] ?? ($m['role'] ?? '')) : ($m['role'] ?? ''),
                    'desc' => $useAr ? ($m['desc_ar'] ?? ($m['desc'] ?? '')) : ($m['desc'] ?? ''),
                    'image' => $m['image'] ?? '',
                ];
            })->values()->all();
    }
}
