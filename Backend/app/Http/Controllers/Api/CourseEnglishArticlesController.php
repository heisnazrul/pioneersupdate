<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CmsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CourseEnglishArticlesController extends Controller
{
    private function toPublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $base = rtrim(config('app.url'), '/');
        $clean = ltrim($path, '/');

        if (Str::startsWith($clean, 'storage/')) {
            return $base . '/' . $clean;
        }

        return $base . '/storage/' . $clean;
    }

    protected function isArabic(Request $request): bool
    {
        return $request->header('X-Lang') === 'ar' || $request->input('lang') === 'ar';
    }

    private function cmsPayload(): array
    {
        $page = CmsPage::query()
            ->forApp('courseenglish')
            ->where('slug', 'articles')
            ->first();

        if (!$page) {
            return ['en' => [], 'ar' => [], 'meta' => []];
        }

        $en = json_decode($page->content, true) ?: [];
        $ar = json_decode($page->ar_content, true) ?: [];

        $normalize = function ($value) use (&$normalize) {
            if (is_array($value)) {
                return array_map($normalize, $value);
            }
            if (is_string($value) && !str_starts_with($value, 'http') && str_contains($value, '/')) {
                return $this->toPublicUrl($value);
            }
            return $value;
        };

        return [
            'en' => $normalize($en),
            'ar' => $normalize($ar),
            'meta' => [
                'title' => $page->title,
                'ar_title' => $page->ar_title,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
            ],
        ];
    }

    public function cms(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_articles_cms_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            return $this->cmsPayload();
        });

        return response()->json($response);
    }

    public function index(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_articles_index_' . $lang . '_' . md5(json_encode($request->all()));

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request) {
            $query = Blog::with(['category', 'tags'])
                ->whereNotNull('published_at');

            if ($request->filled('category') && $request->category !== 'all') {
                $categorySlug = $request->category;
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            if ($request->filled('tag')) {
                $tag = $request->tag;
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('name', $tag);
                });
            }

            if ($request->filled('search')) {
                $term = $request->search;
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('summary', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%")
                        ->orWhere('ar_title', 'like', "%{$term}%")
                        ->orWhere('ar_summary', 'like', "%{$term}%")
                        ->orWhere('ar_content', 'like', "%{$term}%");
                });
            }

            $blogs = $query->latest('published_at')->paginate((int) $request->input('per_page', 9));

            $items = $blogs->getCollection()->map(function (Blog $blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'ar_title' => $blog->ar_title,
                    'slug' => $blog->slug,
                    'summary' => $blog->summary,
                    'ar_summary' => $blog->ar_summary,
                    'content' => $blog->content,
                    'ar_content' => $blog->ar_content,
                    'image' => $this->toPublicUrl($blog->featured_image),
                    'published_at' => optional($blog->published_at)->toISOString(),
                    'date' => optional($blog->published_at)->format('d M Y'),
                    'category' => $blog->category ? [
                        'name' => $blog->category->name,
                        'ar_name' => $blog->category->ar_name,
                        'slug' => $blog->category->slug,
                    ] : null,
                    'tags' => $blog->tags->map(function ($tag) {
                        return [
                            'name' => $tag->name,
                            'ar_name' => $tag->ar_name,
                        ];
                    }),
                    'author' => $blog->publisher ? $blog->publisher->name : 'Admin',
                    'readTime' => '5 min read',
                ];
            })->values();

            return [
                'data' => $items,
                'categories' => $this->categoriesCollection(),
                'meta' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'total' => $blogs->total(),
                ],
            ];
        });

        return response()->json($response);
    }

    public function categories(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_articles_categories_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            return $this->categoriesCollection();
        });

        return response()->json($response);
    }

    public function show(string $slug, Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_articles_show_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            $blog = Blog::where('slug', $slug)
                ->whereNotNull('published_at')
                ->with(['category', 'tags', 'publisher'])
                ->first();

            if (!$blog && is_numeric($slug)) {
                $blog = Blog::where('id', $slug)
                    ->whereNotNull('published_at')
                    ->with(['category', 'tags', 'publisher'])
                    ->firstOrFail();
            } elseif (!$blog) {
                abort(404);
            }

            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'ar_title' => $blog->ar_title,
                'slug' => $blog->slug,
                'summary' => $blog->summary,
                'ar_summary' => $blog->ar_summary,
                'content' => $blog->content,
                'ar_content' => $blog->ar_content,
                'image' => $this->toPublicUrl($blog->featured_image),
                'published_at' => optional($blog->published_at)->toISOString(),
                'date' => optional($blog->published_at)->format('d M Y'),
                'category' => $blog->category ? [
                    'name' => $blog->category->name,
                    'ar_name' => $blog->category->ar_name,
                    'slug' => $blog->category->slug,
                ] : null,
                'tags' => $blog->tags->map(function ($tag) {
                    return [
                        'name' => $tag->name,
                        'ar_name' => $tag->ar_name,
                    ];
                }),
                'author' => $blog->publisher ? $blog->publisher->name : 'Admin',
                'readTime' => '5 min read',
            ];
        });

        return response()->json($response);
    }

    public function category(string $slug): JsonResponse
    {
        $request = request();
        $request->merge(['category' => $slug]);
        return $this->index($request);
    }

    private function categoriesCollection()
    {
        return BlogCategory::whereHas('blogs', function ($q) {
            $q->whereNotNull('published_at');
        })->get()->map(function (BlogCategory $c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'ar_name' => $c->ar_name,
                'slug' => $c->slug,
                'count' => $c->blogs()->whereNotNull('published_at')->count(),
            ];
        })->values();
    }
}
