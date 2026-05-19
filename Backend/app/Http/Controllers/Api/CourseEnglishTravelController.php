<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CourseEnglishTravelController extends Controller
{
    public function show(): JsonResponse
    {
        $cacheKey = 'ce_travel_show';

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $page = CmsPage::query()
                ->forApp('courseenglish')
                ->where('slug', 'travel-and-tourism')
                ->first();

            if (!$page) {
                return null;
            }

            return [
                'content' => json_decode($page->content, true) ?? [],
                'ar_content' => json_decode($page->ar_content, true) ?? [],
                'meta' => [
                    'title' => $page->title,
                    'ar_title' => $page->ar_title,
                    'meta_title' => $page->meta_title,
                    'meta_description' => $page->meta_description,
                ],
            ];
        });

        if (!$response) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json($response);
    }
}
