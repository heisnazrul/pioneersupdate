<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Certification;
use App\Models\City;
use App\Models\CmsPage;
use App\Models\ContactSubmission;
use App\Models\Country;
use App\Models\Destination;
use App\Models\DestinationGuide;
use App\Models\Faq;
use App\Models\FeaturedList;
use App\Models\IntakeTerm;
use App\Models\Level;
use App\Models\Office;
use App\Models\Review;
use App\Models\Scholarship;
use App\Models\SubjectArea;
use App\Models\UniApplication;
use App\Models\University;
use App\Models\UniversityAccommodationRoom;
use App\Models\UniversityCourse;
use App\Models\UniversityCourseCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class UniversityApiController extends Controller
{
    private function decodeJson(?string $json): array
    {
        if (!$json) {
            return [];
        }
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function universityHomeContent(bool $isArabic): array
    {
        $page = CmsPage::where('app', 'university')
            ->where('slug', 'home')
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return [];
        }

        $content = $this->decodeJson($page->content);
        $arContent = $this->decodeJson($page->ar_content);

        if ($isArabic && !empty($arContent)) {
            return $arContent;
        }

        return $content;
    }

    private function isArabic(Request $request): bool
    {
        $lang = strtolower((string) ($request->query('lang') ?: $request->header('X-Lang') ?: $request->cookie('uni_language')));
        if ($lang === 'ar') {
            return true;
        }
        $accept = strtolower((string) $request->header('Accept-Language', ''));
        return Str::startsWith($accept, 'ar');
    }

    private function t(object $model, string $field, bool $isArabic)
    {
        $arField = 'ar_' . $field;
        if ($isArabic && isset($model->{$arField}) && !empty($model->{$arField})) {
            return $model->{$arField};
        }
        return $model->{$field} ?? null;
    }

    private function imageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        if (Str::startsWith($path, '/')) {
            return url($path);
        }
        return asset('storage/' . $path);
    }

    public function hero(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_hero_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $home = $this->universityHomeContent($isArabic);
            $heroCms = is_array($home['hero'] ?? null) ? $home['hero'] : [];

            $featureUniversities = \App\Models\University::with('city')->active()->featured()->get()->map(fn($u) => [
                'name' => $this->t($u, 'name', $isArabic),
                'city_name' => $u->city ? $this->t($u->city, 'name', $isArabic) : null,
                'logo' => $this->imageUrl($u->logo),
            ]);

            $universities = \App\Models\University::with('city')->active()->get()->map(fn($u) => [
                'name' => $this->t($u, 'name', $isArabic),
                'city_name' => $u->city ? $this->t($u->city, 'name', $isArabic) : null,
                'logo' => $this->imageUrl($u->logo),
            ]);

            $featureCountries = \App\Models\Country::active()->popular()
                ->whereHas('universities', function ($query) {
                    $query->where('is_active', true);
                })
                ->get()->map(fn($c) => [
                    'name' => $this->t($c, 'name', $isArabic),
                    'flag' => $this->imageUrl($c->flag),
                ]);

            $levels = \App\Models\Level::where('is_active', true)->orderBy('sort_order')->get()->map(fn($l) => [
                'name' => $this->t($l, 'name', $isArabic),
            ]);

            $courses = \App\Models\UniversityCourseCatalog::query()
                ->where('is_active', true)
                ->whereNotNull('subject_area_id')
                ->whereHas('subjectArea', fn($q) => $q->where('is_active', true))
                ->whereHas('universityCourses', fn($q) => $q->where('is_active', true))
                ->orderBy('name')
                ->get()
                ->map(fn($catalog) => [
                    'name' => $isArabic
                        ? ($catalog->ar_name ?: $catalog->name)
                        : ($catalog->name ?: $catalog->ar_name),
                    'subject_area_id' => $catalog->subject_area_id,
                ])
                ->filter(fn($c) => !empty($c['name']))
                ->values();

            $intakes = \App\Models\IntakeTerm::where('is_active', true)->orderBy('sort_order')->get()->map(fn($i) => [
                'name' => $this->t($i, 'name', $isArabic),
            ]);

            return [
                'headline' => $heroCms['headline'] ?? '',
                'subheadline' => $heroCms['subheadline'] ?? '',
                'background_image' => $heroCms['background_image'] ?? '',
                'figure_image' => $heroCms['figure_image'] ?? '',
                'search_label' => $heroCms['search_label'] ?? '',
                'search_placeholder_courses' => $heroCms['search_placeholder_courses'] ?? '',
                'search_placeholder_universities' => $heroCms['search_placeholder_universities'] ?? '',
                'country_label' => $heroCms['country_label'] ?? '',
                'country_placeholder' => $heroCms['country_placeholder'] ?? '',
                'level_label' => $heroCms['level_label'] ?? '',
                'level_placeholder' => $heroCms['level_placeholder'] ?? '',
                'intake_label' => $heroCms['intake_label'] ?? '',
                'intake_placeholder' => $heroCms['intake_placeholder'] ?? '',
                'tab_courses' => $heroCms['tab_courses'] ?? '',
                'tab_universities' => $heroCms['tab_universities'] ?? '',
                'search_button_text' => $heroCms['search_button_text'] ?? '',
                'feature_universities' => $featureUniversities,
                'universities' => $universities,
                'feature_countries' => $featureCountries,
                'levels' => $levels,
                'courses' => $courses,
                'intakes' => $intakes,
            ];
        });

        return response()->json($response);
    }

    public function homeCms(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_cms_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $content = $this->universityHomeContent($isArabic);

            if (empty($content)) {
                return ['status' => 404, 'message' => 'Home CMS page not found'];
            }

            return ['status' => 200, 'data' => $content];
        });

        return response()->json($response, isset($response['status']) && $response['status'] === 404 ? 404 : 200);
    }

    public function certificate(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_certificate_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Certification::query()->get()->map(fn($c) => [
                'title' => $this->t($c, 'title', $isArabic),
                'image' => $this->imageUrl($c->certificate_image),
                'link' => $c->certification_link,
            ]);
        });
        
        return response()->json($response);
    }

    public function homeDestinations(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_destinations_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Destination::active()->select('name', 'ar_name', 'image_url', 'slug')->get()->map(fn($d) => [
                'name' => $this->t($d, 'name', $isArabic),
                'image' => $this->imageUrl($d->image_url),
                'slug' => $d->slug,
            ]);
        });
        
        return response()->json($response);
    }

    public function homeUniversities(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_universities_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\University::with(['city', 'country'])
                ->active()->featured()->get()->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $this->t($u, 'name', $isArabic),
                    'slug' => $u->slug,
                    'logo' => $this->imageUrl($u->logo),
                    'rank' => $u->qs_ranking ?: $u->the_ranking ?: $u->shanghai_ranking,
                    'address' => trim(($u->city ? $this->t($u->city, 'name', $isArabic) : '') . (($u->city && $u->country) ? ', ' : '') . ($u->country ? $this->t($u->country, 'name', $isArabic) : '')),
                    'famous_for' => $this->t($u, 'famous_for', $isArabic),
                ]);
        });
        
        return response()->json($response);
    }

    public function homeReviews(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_reviews_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $all = \App\Models\Review::active()->orderBy('created_at', 'desc')->get();
            $video = $all->filter(fn($r) => !empty($r->video_url))->values()->map(fn($r) => [
                'id' => $r->id,
                'name' => $this->t($r, 'name', $isArabic),
                'university_name' => $r->university_name ?: $this->t($r, 'institute_name', $isArabic),
                'course_name' => $r->course_name,
                'country_name' => $r->country_name,
                'thumbnail' => $this->imageUrl($r->thumbnail),
                'duration' => '2:00',
                'review_text' => $this->t($r, 'review_text', $isArabic),
                'video_url' => $r->video_url,
                'video_iframe' => $r->video_iframe,
            ]);
            $text = $all->values()->map(fn($r) => [
                'id' => $r->id,
                'name' => $this->t($r, 'name', $isArabic),
                'role' => $r->course_name ?? ($isArabic ? 'طالب' : 'Student'),
                'title' => $this->t($r, 'title', $isArabic) ?: ($isArabic ? 'تجربة رائعة' : 'Great Experience'),
                'text' => $this->t($r, 'review_text', $isArabic),
                'rating' => $r->rating,
            ]);
            return ['video_reviews' => $video, 'reviews' => $text];
        });
        
        return response()->json($response);
    }

    public function homeScholarships(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_scholarships_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Scholarship::active()->orderBy('deadline_date')->get()->map(function ($s) use ($isArabic) {
                $amount = 'Variable';
                if ($s->amount_type === 'fixed') {
                    $amount = $s->currency . number_format((float) $s->amount_value);
                } elseif ($s->amount_type === 'percentage') {
                    $amount = $s->amount_value . '%';
                }
                return [
                    'id' => $s->id,
                    'title' => $this->t($s, 'name', $isArabic),
                    'slug' => $s->slug,
                    'amount' => $amount,
                    'deadline' => optional($s->deadline_date)->format('d M Y'),
                    'tags' => $s->tags ?: [],
                ];
            });
        });
        
        return response()->json($response);
    }

    public function homeBlogs(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_blogs_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Blog::whereNotNull('published_at')->latest('published_at')->take(6)->get()->map(fn($b) => [
                'id' => $b->id,
                'title' => $this->t($b, 'title', $isArabic),
                'slug' => $b->slug,
                'summary' => $this->t($b, 'summary', $isArabic),
                'image' => $this->imageUrl($b->featured_image),
                'published_at' => optional($b->published_at)->format('d M Y'),
            ]);
        });
        
        return response()->json($response);
    }

    public function homeFaqs(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_home_faqs_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Faq::query()->latest('id')->take(20)->get()->map(fn($f) => [
                'id' => $f->id,
                'category' => $this->t($f, 'category', $isArabic),
                'question' => $this->t($f, 'question', $isArabic),
                'answer' => $this->t($f, 'answer', $isArabic),
            ]);
        });
        
        return response()->json($response);
    }

    public function countries(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_countries_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Country::select('id', 'name', 'ar_name', 'slug', 'country_code', 'currency_code', 'is_popular')
                ->whereHas('universities', fn($q) => $q->where('is_active', true))
                ->orderBy('name')
                ->get()
                ->map(fn($c) => array_merge($c->toArray(), ['name' => $this->t($c, 'name', $isArabic)]));
        });
        
        return response()->json($response);
    }

    public function intakes(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_intakes_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\IntakeTerm::select('id', 'name', 'ar_name', 'key', 'month_num')
                ->whereHas('courses')
                ->orderBy('month_num')
                ->get()
                ->map(fn($i) => array_merge($i->toArray(), ['name' => $this->t($i, 'name', $isArabic)]));
        });
        
        return response()->json($response);
    }

    public function levels(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_levels_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Level::select('id', 'name', 'ar_name', 'key', 'sort_order')
                ->whereHas('courses')
                ->orderBy('sort_order')
                ->get()
                ->map(fn($l) => array_merge($l->toArray(), ['name' => $this->t($l, 'name', $isArabic)]));
        });
        
        return response()->json($response);
    }

    public function subjectAreas(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_subject_areas_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\SubjectArea::select('id', 'name', 'ar_name', 'key')->orderBy('name')->get()
                ->map(fn($a) => array_merge($a->toArray(), ['name' => $this->t($a, 'name', $isArabic)]));
        });
        
        return response()->json($response);
    }

    public function cities(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_cities_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\City::select('id', 'name', 'ar_name', 'country_id')
                ->whereHas('universities', fn($q) => $q->where('is_active', true)->whereHas('courses', fn($cq) => $cq->where('is_active', true)))
                ->orderBy('name')
                ->get()
                ->map(fn($c) => array_merge($c->toArray(), ['name' => $this->t($c, 'name', $isArabic)]));
        });
        
        return response()->json($response);
    }

    public function courses(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_courses_' . md5(json_encode($request->all())) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request, $isArabic) {
            $query = \App\Models\UniversityCourse::with(['university.city.country', 'level', 'courseCatalog.subjectArea', 'intakeTerms'])->active();

            if ($request->filled('keyword')) {
                $keyword = $request->input('keyword');
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('courseCatalog', fn($cq) => $cq->where('name', 'like', "%{$keyword}%")->orWhere('ar_name', 'like', "%{$keyword}%"))
                        ->orWhereHas('university', fn($uq) => $uq->where('name', 'like', "%{$keyword}%")->orWhere('ar_name', 'like', "%{$keyword}%"));
                });
            }
            if ($request->filled('level')) {
                $v = $request->input('level');
                $query->whereHas('level', fn($q) => $q->where('key', $v)->orWhere('name', 'like', "%{$v}%")->orWhere('ar_name', 'like', "%{$v}%"));
            }
            if ($request->filled('destination')) {
                $v = $request->input('destination');
                $query->whereHas('university.country', fn($q) => $q->where('name', $v)->orWhere('ar_name', $v)->orWhere('country_code', $v));
            }
            if ($request->filled('city')) {
                $v = $request->input('city');
                $query->whereHas('university.city', fn($q) => $q->where('name', $v)->orWhere('ar_name', $v));
            }
            if ($request->filled('discipline')) {
                $v = $request->input('discipline');
                $query->whereHas('courseCatalog.subjectArea', fn($q) => $q->where('name', 'like', "%{$v}%")->orWhere('ar_name', 'like', "%{$v}%")->orWhere('key', $v));
            }
            if ($request->filled('intake')) {
                $v = $request->input('intake');
                $query->whereHas('intakeTerms', fn($q) => $q->where('key', $v)->orWhere('name', 'like', "%{$v}%")->orWhere('ar_name', 'like', "%{$v}%"));
            }

            $sort = $request->input('sort');
            if ($sort === 'duration_asc') {
                $query->orderBy('duration_value');
            } elseif ($sort === 'duration_desc') {
                $query->orderByDesc('duration_value');
            } else {
                $query->latest('id');
            }

            $pageSize = min((int) $request->input('pageSize', 12), 50);
            $paginator = $query->paginate($pageSize);
            $data = collect($paginator->items())->map(fn($c) => [
                'id' => (string) $c->id,
                'name' => $isArabic ? ($c->ar_name ?: $c->name) : ($c->name ?: $c->ar_name),
                'slug' => $c->courseCatalog?->slug,
                'university' => $this->t($c->university, 'name', $isArabic),
                'universityId' => $c->university_id,
                'universityLogo' => $this->imageUrl($c->university?->logo),
                'location' => trim(($c->university?->city ? $this->t($c->university->city, 'name', $isArabic) : '') . (($c->university?->city && $c->university?->country) ? ', ' : '') . ($c->university?->country ? $this->t($c->university->country, 'name', $isArabic) : '')),
                'countryCode' => $c->university?->country?->country_code,
                'level' => $c->level ? $this->t($c->level, 'name', $isArabic) : null,
                'levelKey' => $c->level?->key,
                'discipline' => $c->courseCatalog?->subjectArea ? $this->t($c->courseCatalog->subjectArea, 'name', $isArabic) : null,
                'duration' => trim(($c->duration_value ?: '') . ' ' . ($c->duration_unit ?: '')),
                'description' => $this->t($c, 'overview', $isArabic),
                'languageRequirement' => $c->language_requirement,
                'degreeRequirement' => $c->degree_requirement,
                'intake' => $c->intakeTerms->map(fn($i) => $this->t($i, 'name', $isArabic))->values()->all(),
                'applicationDeadline' => optional($c->intakeTerms->first()?->pivot->deadline_date)->format('Y-m-d'),
            ])->values();

            return [
                'data' => $data,
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ];
        });

        return response()->json($response);
    }

    public function courseShow(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_course_show_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug, $isArabic) {
            $course = \App\Models\UniversityCourse::with(['university.city.country', 'level', 'courseCatalog.subjectArea', 'intakeTerms'])
                ->whereHas('courseCatalog', fn($q) => $q->where('slug', $slug))
                ->active()
                ->firstOrFail();
            return [
                'id' => (string) $course->id,
                'name' => $isArabic ? ($course->ar_name ?: $course->name) : ($course->name ?: $course->ar_name),
                'slug' => $course->courseCatalog?->slug,
                'university' => $this->t($course->university, 'name', $isArabic),
                'universityId' => $course->university_id,
                'universityLogo' => $this->imageUrl($course->university?->logo),
                'location' => trim(($course->university?->city ? $this->t($course->university->city, 'name', $isArabic) : '') . (($course->university?->city && $course->university?->country) ? ', ' : '') . ($course->university?->country ? $this->t($course->university->country, 'name', $isArabic) : '')),
                'countryCode' => $course->university?->country?->country_code,
                'level' => $course->level ? $this->t($course->level, 'name', $isArabic) : null,
                'levelKey' => $course->level?->key,
                'discipline' => $course->courseCatalog?->subjectArea ? $this->t($course->courseCatalog->subjectArea, 'name', $isArabic) : null,
                'duration' => trim(($course->duration_value ?: '') . ' ' . ($course->duration_unit ?: '')),
                'description' => $this->t($course, 'overview', $isArabic),
                'languageRequirement' => $course->language_requirement,
                'degreeRequirement' => $course->degree_requirement,
                'intake' => $course->intakeTerms->map(fn($i) => $this->t($i, 'name', $isArabic))->values()->all(),
                'applicationDeadline' => optional($course->intakeTerms->first()?->pivot->deadline_date)->format('Y-m-d'),
            ];
        });

        return response()->json($response);
    }

    public function universities(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_universities_' . md5(json_encode($request->all())) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request, $isArabic) {
            $query = \App\Models\University::with(['city.country', 'courses.level'])->active();
            if ($request->filled('keyword')) {
                $k = $request->input('keyword');
                $query->where(fn($q) => $q->where('name', 'like', "%{$k}%")->orWhere('ar_name', 'like', "%{$k}%"));
            }
            if ($request->filled('destination')) {
                $v = $request->input('destination');
                $query->whereHas('country', fn($q) => $q->where('name', $v)->orWhere('ar_name', $v)->orWhere('country_code', $v));
            }
            if ($request->filled('city')) {
                $v = $request->input('city');
                $query->whereHas('city', fn($q) => $q->where('name', $v)->orWhere('ar_name', $v));
            }
            if ($request->filled('rankingMin')) {
                $query->where(function ($q) use ($request) {
                    $q->where('qs_ranking', '>=', $request->integer('rankingMin'))
                        ->orWhere('the_ranking', '>=', $request->integer('rankingMin'))
                        ->orWhere('shanghai_ranking', '>=', $request->integer('rankingMin'));
                });
            }
            if ($request->filled('rankingMax')) {
                $query->where(function ($q) use ($request) {
                    $q->where('qs_ranking', '<=', $request->integer('rankingMax'))
                        ->orWhere('the_ranking', '<=', $request->integer('rankingMax'))
                        ->orWhere('shanghai_ranking', '<=', $request->integer('rankingMax'));
                });
            }
            $sort = $request->input('sort');
            if ($sort === 'rank_desc') {
                $query->orderByDesc('qs_ranking');
            } else {
                $query->orderBy('qs_ranking')->orderBy('name');
            }
            $pageSize = min((int) $request->input('pageSize', 12), 50);
            $paginator = $query->paginate($pageSize);
            $data = collect($paginator->items())->map(fn($u) => [
                'id' => $u->id,
                'name' => $this->t($u, 'name', $isArabic),
                'slug' => $u->slug,
                'countryCode' => $u->country?->country_code,
                'location' => trim(($u->city ? $this->t($u->city, 'name', $isArabic) : '') . (($u->city && $u->country) ? ', ' : '') . ($u->country ? $this->t($u->country, 'name', $isArabic) : '')),
                'rank' => $u->qs_ranking ? '#' . $u->qs_ranking : null,
                'logoUrl' => $this->imageUrl($u->logo),
                'isFeatured' => (bool) $u->is_featured,
                'famousFor' => $this->t($u, 'famous_for', $isArabic),
            ])->values();

            return [
                'data' => $data,
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ];
        });

        return response()->json($response);
    }

    public function universityShow(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_university_show_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug, $isArabic) {
            $u = \App\Models\University::with([
                'city.country',
                'courses.level',
                'courses.intakeTerms',
                'courses.courseCatalog.subjectArea',
            ])->where('slug', $slug)->active()->firstOrFail();

            // Group courses by course_catalog_id → one card per catalog, with all levels listed
            $catalogMap = [];
            foreach ($u->courses as $c) {
                $catalogId = $c->course_catalog_id;
                if (!isset($catalogMap[$catalogId])) {
                    $catalogMap[$catalogId] = [
                        'catalogId' => $catalogId,
                        'name' => $isArabic ? ($c->ar_name ?: $c->name) : ($c->name ?: $c->ar_name),
                        'slug' => $c->courseCatalog?->slug,
                        'discipline' => $c->courseCatalog?->subjectArea
                            ? $this->t($c->courseCatalog->subjectArea, 'name', $isArabic)
                            : null,
                        'levels' => [],
                    ];
                }
                if ($c->level) {
                    $catalogMap[$catalogId]['levels'][] = [
                        'courseId' => (string) $c->id,
                        'levelName' => $this->t($c->level, 'name', $isArabic),
                        'levelKey' => $c->level->key,
                        'duration' => trim(($c->duration_value ?: '') . ' ' . ($c->duration_unit ?: '')),
                    ];
                }
            }

            // Sort levels within each catalog by level name
            $coursesCatalog = collect(array_values($catalogMap))->map(function ($cat) {
                usort($cat['levels'], fn($a, $b) => strcmp($a['levelName'], $b['levelName']));
                return $cat;
            })->values();

            // Legacy flat courses list (first 6) for backward compat
            $courses = $u->courses->take(6)->map(fn($c) => [
                'id' => $c->id,
                'name' => $isArabic ? ($c->ar_name ?: $c->name) : ($c->name ?: $c->ar_name),
                'slug' => $c->courseCatalog?->slug,
                'level' => $c->level ? $this->t($c->level, 'name', $isArabic) : null,
                'duration' => trim(($c->duration_value ?: '') . ' ' . ($c->duration_unit ?: '')),
            ])->values();

            return [
                'id' => $u->id,
                'name' => $this->t($u, 'name', $isArabic),
                'slug' => $u->slug,
                'countryCode' => $u->country?->country_code,
                'location' => trim(($u->city ? $this->t($u->city, 'name', $isArabic) : '') . (($u->city && $u->country) ? ', ' : '') . ($u->country ? $this->t($u->country, 'name', $isArabic) : '')),
                'rank' => $u->qs_ranking ? '#' . $u->qs_ranking : ($u->the_ranking ? '#' . $u->the_ranking : ($u->shanghai_ranking ? '#' . $u->shanghai_ranking : null)),
                'qsRanking' => $u->qs_ranking,
                'theRanking' => $u->the_ranking,
                'shanghaiRanking' => $u->shanghai_ranking,
                'logoUrl' => $this->imageUrl($u->logo),
                'coverImageUrl' => $this->imageUrl($u->cover_image),
                'description' => null,
                'establishedYear' => $u->established_year,
                'type' => ucfirst((string) $u->type),
                'studentCount' => null,
                'employmentRate' => null,
                'website' => $u->website,
                'fees' => $this->t($u, 'fees', $isArabic),
                'famousFor' => $this->t($u, 'famous_for', $isArabic),
                'courses' => $courses,
                'coursesCatalog' => $coursesCatalog,
            ];
        });

        return response()->json($response);
    }


    public function destinations(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_destinations_' . md5(json_encode($request->all())) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            return \App\Models\Destination::with(['country', 'features', 'stats', 'intakes', 'requirements', 'faqs'])->active()->get()->map(function ($d) use ($isArabic) {
                $topUniversities = collect();
                if ($d->country_id) {
                    $topUniversities = \App\Models\University::with(['country', 'city'])->where('country_id', $d->country_id)->active()->orderBy('qs_ranking')->take(3)->get()->map(fn($u) => [
                        'id' => (string) $u->id,
                        'name' => $this->t($u, 'name', $isArabic),
                        'rank' => $u->qs_ranking ? '#' . $u->qs_ranking . ' ' . ($u->country?->country_code ?: '') : '',
                        'location' => $u->city ? $this->t($u->city, 'name', $isArabic) : '',
                        'countryCode' => $u->country?->country_code ?: '',
                        'cityId' => (string) ($u->city_id ?: ''),
                        'worldRank' => $u->qs_ranking,
                        'logoUrl' => $this->imageUrl($u->logo),
                        'slug' => $u->slug,
                    ]);
                }
                return [
                    'id' => (string) $d->id,
                    'slug' => $d->slug,
                    'name' => $this->t($d, 'name', $isArabic),
                    'countryCode' => strtolower((string) $d->country?->country_code),
                    'description' => $this->t($d, 'description', $isArabic),
                    'imageUrl' => $this->imageUrl($d->image_url),
                    'features' => $d->features->map(fn($f) => $this->t($f, 'feature', $isArabic))->values(),
                    'shortPitch' => $this->t($d, 'short_pitch', $isArabic),
                    'tuitionRange' => $this->t($d, 'tuition_range', $isArabic),
                    'visaTimeline' => $this->t($d, 'visa_timeline', $isArabic),
                    'workRights' => $this->t($d, 'work_rights', $isArabic),
                    'scholarships' => $this->t($d, 'scholarships_summary', $isArabic),
                    'popularPrograms' => [],
                    'topUniversities' => $topUniversities,
                    'intakeTimeline' => $d->intakes->map(fn($i) => ['month' => $this->t($i, 'month', $isArabic), 'event' => $this->t($i, 'event', $isArabic)])->values(),
                    'requirements' => $d->requirements->map(fn($r) => $this->t($r, 'requirement', $isArabic))->values(),
                    'faqs' => $d->faqs->map(fn($f) => ['id' => (string) $f->id, 'question' => $this->t($f, 'question', $isArabic), 'answer' => $this->t($f, 'answer', $isArabic)])->values(),
                    'region' => $this->t($d, 'region', $isArabic),
                    'stats' => $d->stats->map(fn($s) => ['label' => $this->t($s, 'label', $isArabic), 'value' => $this->t($s, 'value', $isArabic)])->values(),
                ];
            });
        });

        return response()->json($response);
    }

    public function destinationShow(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_destination_show_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request, $slug, $isArabic) {
            $d = \App\Models\Destination::with(['country', 'features', 'stats', 'intakes', 'requirements', 'faqs', 'guide'])->where('slug', $slug)->firstOrFail();
            
            // Re-fetch the destinations without cache inside the closure, or directly extract here
            $topUniversities = collect();
            if ($d->country_id) {
                $topUniversities = \App\Models\University::with(['country', 'city'])->where('country_id', $d->country_id)->active()->orderBy('qs_ranking')->take(3)->get()->map(fn($u) => [
                    'id' => (string) $u->id,
                    'name' => $this->t($u, 'name', $isArabic),
                    'rank' => $u->qs_ranking ? '#' . $u->qs_ranking . ' ' . ($u->country?->country_code ?: '') : '',
                    'location' => $u->city ? $this->t($u->city, 'name', $isArabic) : '',
                    'countryCode' => $u->country?->country_code ?: '',
                    'cityId' => (string) ($u->city_id ?: ''),
                    'worldRank' => $u->qs_ranking,
                    'logoUrl' => $this->imageUrl($u->logo),
                    'slug' => $u->slug,
                ]);
            }

            $item = [
                'id' => (string) $d->id,
                'slug' => $d->slug,
                'name' => $this->t($d, 'name', $isArabic),
                'countryCode' => strtolower((string) $d->country?->country_code),
                'description' => $this->t($d, 'description', $isArabic),
                'imageUrl' => $this->imageUrl($d->image_url),
                'features' => $d->features->map(fn($f) => $this->t($f, 'feature', $isArabic))->values(),
                'shortPitch' => $this->t($d, 'short_pitch', $isArabic),
                'tuitionRange' => $this->t($d, 'tuition_range', $isArabic),
                'visaTimeline' => $this->t($d, 'visa_timeline', $isArabic),
                'workRights' => $this->t($d, 'work_rights', $isArabic),
                'scholarships' => $this->t($d, 'scholarships_summary', $isArabic),
                'popularPrograms' => [],
                'topUniversities' => $topUniversities,
                'intakeTimeline' => $d->intakes->map(fn($i) => ['month' => $this->t($i, 'month', $isArabic), 'event' => $this->t($i, 'event', $isArabic)])->values(),
                'requirements' => $d->requirements->map(fn($r) => $this->t($r, 'requirement', $isArabic))->values(),
                'faqs' => $d->faqs->map(fn($f) => ['id' => (string) $f->id, 'question' => $this->t($f, 'question', $isArabic), 'answer' => $this->t($f, 'answer', $isArabic)])->values(),
                'region' => $this->t($d, 'region', $isArabic),
                'stats' => $d->stats->map(fn($s) => ['label' => $this->t($s, 'label', $isArabic), 'value' => $this->t($s, 'value', $isArabic)])->values(),
            ];

            $item['guide'] = $d->guide ? [
                'title' => $this->t($d->guide, 'title', $isArabic),
                'fileUrl' => $this->imageUrl($d->guide->file_path),
                'year' => $d->guide->year,
            ] : null;

            return $item;
        });

        return response()->json($response);
    }

    public function scholarships(Request $request): JsonResponse
    {
        return $this->homeScholarships($request);
    }

    public function cmsPage(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_cms_page_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug, $isArabic) {
            $page = \App\Models\CmsPage::where('app', 'university')
                ->where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();

            $content = $this->decodeJson($page->content);
            $arContent = $this->decodeJson($page->ar_content);
            $activeContent = $isArabic && !empty($arContent) ? $arContent : $content;

            return [
                'status' => 'success',
                'data' => [
                    'id' => $page->id,
                    'slug' => $page->slug,
                    'title' => $isArabic ? ($page->ar_title ?: $page->title) : $page->title,
                    'meta_title' => $page->meta_title,
                    'meta_description' => $page->meta_description,
                    'content' => $activeContent,
                ],
            ];
        });

        return response()->json($response);
    }

    public function offices(): JsonResponse
    {
        $cacheKey = 'uni_offices';

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            return \App\Models\Office::all()->map(function ($o) {
                $o->image = $this->imageUrl($o->image);
                return $o;
            });
        });

        return response()->json($response);
    }

    public function officeShow(string $slug): JsonResponse
    {
        $cacheKey = 'uni_office_show_' . $slug;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            $o = \App\Models\Office::where('slug', $slug)->firstOrFail();
            $o->image = $this->imageUrl($o->image);
            return $o;
        });

        return response()->json($response);
    }

    public function navbarDestinations(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_navbar_destinations_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $destinations = \App\Models\Destination::whereHas('country', fn($q) => $q->where('is_popular', true)->where('is_active', true))
                ->with('country')
                ->where('is_active', true)
                ->limit(9)
                ->get();
            $data = $destinations->map(fn($d) => [
                'name' => $d->country ? $this->t($d->country, 'name', $isArabic) : $this->t($d, 'name', $isArabic),
                'slug' => $d->slug,
                'flag' => $d->country ? $this->imageUrl($d->country->flag) : null,
                'country_code' => strtolower((string) ($d->country->country_code ?? '')),
                'pitch' => $this->t($d, 'short_pitch', $isArabic),
            ]);
            return ['status' => 200, 'data' => $data];
        });

        return response()->json($response);
    }

    public function destinationGuides(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_destination_guides_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $guides = \App\Models\DestinationGuide::active()->with('destination')->latest()->get()->map(fn($g) => [
                'id' => $g->id,
                'title' => $this->t($g, 'title', $isArabic),
                'file_url' => $this->imageUrl($g->file_path),
                'year' => $g->year,
                'destination' => $g->destination ? $this->t($g->destination, 'name', $isArabic) : ($isArabic ? 'عام' : 'General'),
                'type' => 'PDF',
                'size' => 'Unknown',
                'icon' => 'faFilePdf',
                'color' => 'text-red-500',
                'bg' => 'bg-red-50',
            ]);
            return ['data' => $guides];
        });

        return response()->json($response);
    }

    public function contactSubmit(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'message' => ['required', 'string'],
        ]);
        $submission = ContactSubmission::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.',
            'data' => $submission,
        ], 201);
    }

    public function accommodationRooms(): JsonResponse
    {
        $cacheKey = 'uni_accommodation_rooms';

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            return \App\Models\UniversityAccommodationRoom::query()->get()->map(function ($r) {
                $r->image = $this->imageUrl($r->image);
                return $r;
            });
        });

        return response()->json($response);
    }

    public function accommodationRoomShow(string $slug): JsonResponse
    {
        $cacheKey = 'uni_accommodation_room_show_' . $slug;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            $room = \App\Models\UniversityAccommodationRoom::where('slug', $slug)->firstOrFail();
            $room->image = $this->imageUrl($room->image);
            return $room;
        });

        return response()->json($response);
    }

    public function contactMeta(): JsonResponse
    {
        return response()->json(config('app.name'));
    }

    public function simpleFaqs(Request $request): JsonResponse
    {
        return $this->homeFaqs($request);
    }

    public function featureByKey(string $key, Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'uni_feature_by_key_' . $key . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($key, $isArabic) {
            return \App\Models\FeaturedList::where('is_active', true)->where('key', $key)->get()->map(fn($f) => [
                'id' => $f->id,
                'name' => $this->t($f, 'name', $isArabic),
                'key' => $f->key,
            ]);
        });

        return response()->json($response);
    }
}
