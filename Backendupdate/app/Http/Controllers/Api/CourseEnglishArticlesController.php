<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseEnglishArticlesController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Blog::query()
            ->whereNotNull('published_at')
            ->whereIn('audience_scope', ['school', 'all'])
            ->with(['category', 'tags', 'publisher']);

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $slug = (string) $request->input('category');
            $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
        }

        if ($request->filled('search')) {
            $term = (string) $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('summary', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%")
                    ->orWhere('ar_title', 'like', "%{$term}%")
                    ->orWhere('ar_summary', 'like', "%{$term}%")
                    ->orWhere('ar_content', 'like', "%{$term}%");
            });
        }

        $blogs = $query
            ->latest('published_at')
            ->paginate((int) $request->input('per_page', 9));

        return response()->json([
            'data' => $blogs->getCollection()->map(fn (Blog $blog) => $this->support->blogCard($blog))->values(),
            'categories' => $this->categoriesCollection(),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    public function categories(): JsonResponse
    {
        return response()->json($this->categoriesCollection());
    }

    public function show(string $slug): JsonResponse
    {
        $blog = Blog::query()
            ->whereNotNull('published_at')
            ->whereIn('audience_scope', ['school', 'all'])
            ->with(['category', 'tags', 'publisher'])
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
                if (is_numeric($slug)) {
                    $q->orWhere('id', (int) $slug);
                }
            })
            ->first();

        if (!$blog) {
            return response()->json(['message' => 'Article not found.'], 404);
        }

        $payload = $this->support->blogCard($blog);
        $payload['tags'] = $blog->tags->map(fn ($tag) => [
            'name' => $tag->name,
            'ar_name' => $tag->ar_name,
        ])->values();
        $payload['category'] = $blog->category ? [
            'name' => $blog->category->name,
            'ar_name' => $blog->category->ar_name,
            'slug' => $blog->category->slug,
        ] : null;
        $payload['readTime'] = '5 min read';

        return response()->json($payload);
    }

    public function category(string $slug, Request $request): JsonResponse
    {
        $request->merge(['category' => $slug]);

        return $this->index($request);
    }

    private function categoriesCollection()
    {
        return BlogCategory::query()
            ->active()
            ->whereHas('blogs', function ($q) {
                $q->whereNotNull('published_at')
                    ->whereIn('audience_scope', ['school', 'all']);
            })
            ->orderBy('display_order')
            ->orderBy('name')
            ->get()
            ->map(fn (BlogCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'ar_name' => $category->ar_name,
                'slug' => $category->slug,
                'count' => $category->blogs()
                    ->whereNotNull('published_at')
                    ->whereIn('audience_scope', ['school', 'all'])
                    ->count(),
            ])
            ->values();
    }
}
