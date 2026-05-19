<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    private function getImageUrl(?string $path)
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    public function index(Request $request)
    {
        // Cache key based on all request parameters (e.g. page, category, tag, search)
        $cacheKey = 'blogs_index_' . md5(json_encode($request->all()));

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request) {
            $query = Blog::with(['category', 'tags'])
                ->whereNotNull('published_at');

            // Filter by Category
            if ($request->has('category') && $request->category !== 'all') {
                $categorySlug = $request->category;
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            // Filter by Tag
            if ($request->has('tag')) {
                $tag = $request->tag;
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('name', $tag);
                });
            }

            // Search
            if ($request->has('search')) {
                $term = $request->search;
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                        ->orWhere('summary', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%");
                });
            }

            $blogs = $query->latest('published_at')->paginate(9);

            // Normalize response
            $data = $blogs->getCollection()->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'summary' => $blog->summary,
                    'image' => $this->getImageUrl($blog->featured_image),
                    'published_at' => $blog->published_at->format('d M Y'),
                    'date' => $blog->published_at->format('d M Y'), // normalized field
                    'category' => $blog->category ? $blog->category->name : 'Blog', // simplified for card
                    'author' => $blog->publisher ? $blog->publisher->name : 'Admin',
                    'readTime' => '5 min read', // Mock or calculate
                ];
            });

            return [
                'data' => $data,
                'meta' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'total' => $blogs->total(),
                ]
            ];
        });

        return response()->json($response);
    }

    public function categories()
    {
        $cacheKey = 'blog_categories';

        $categories = Cache::remember($cacheKey, now()->addWeek(), function () {
            return \App\Models\BlogCategory::whereHas('blogs', function ($q) {
                $q->whereNotNull('published_at');
            })->get()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'count' => $c->blogs()->whereNotNull('published_at')->count(),
                ];
            });
        });

        return response()->json($categories);
    }

    public function show($slug)
    {
        $cacheKey = 'blog_show_' . $slug;

        $data = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            // Try to find by slug, if not found (maybe generic ID is passed?), try ID if numeric
            // But user request specifically said "slug". The seeder might generate slugs.
            // Let's stick to slug first.
            $blog = Blog::where('slug', $slug)
                ->whereNotNull('published_at')
                ->with(['category', 'tags', 'publisher'])
                ->first();

            // Fallback for ID if slug lookup fails and slug looks like an ID (optional, but safer given some code usages saw ID fallback)
            if (!$blog && is_numeric($slug)) {
                $blog = Blog::where('id', $slug)
                    ->whereNotNull('published_at')
                    ->with(['category', 'tags', 'publisher'])
                    ->firstOrFail();
            } else if (!$blog) {
                abort(404);
            }

            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'summary' => $blog->summary,
                'excerpt' => $blog->summary, // normalized
                'content' => $blog->content,
                'image' => $this->getImageUrl($blog->featured_image),
                'imageUrl' => $this->getImageUrl($blog->featured_image), // normalized
                'published_at' => $blog->published_at->format('d M Y'),
                'date' => $blog->published_at->format('d M Y'), // normalized
                'category' => $blog->category ? [
                    'name' => $blog->category->name,
                    'slug' => $blog->category->slug
                ] : 'Blog',
                'audience_scope' => $blog->audience_scope,
                'tags' => $blog->tags->map(function ($t) {
                    return $t->name;
                }),
                'publisher' => $blog->publisher ? $blog->publisher->name : 'Admin',
                'author' => $blog->publisher ? $blog->publisher->name : 'Admin', // normalized
                'readTime' => '5 min read',
            ];
        });

        return response()->json($data);
    }
}
