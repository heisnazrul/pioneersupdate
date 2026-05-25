<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolCourse;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Http\JsonResponse;

class CourseSatController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support
    ) {
    }

    public function home(): JsonResponse
    {
        $courses = LanguageSchoolCourse::query()
            ->active()
            ->whereHas('branch', fn ($query) => $query
                ->where('is_active', 'yes')
                ->whereHas('school', fn ($schoolQuery) => $schoolQuery->active())
                ->whereHas('city', fn ($cityQuery) => $cityQuery
                    ->active()
                    ->whereHas('country', fn ($countryQuery) => $countryQuery->active())))
            ->whereHas('category', fn ($query) => $query->active())
            ->with([
                'branch.school:id,name_en,name_ar,slug,logo_url',
                'branch.city:id,country_id,name,ar_name,slug',
                'branch.city.country:id,name,ar_name,slug,flag',
                'category:id,name_en,name_ar,slug',
            ])
            ->get();

        $branches = $courses
            ->pluck('branch')
            ->filter()
            ->unique('id')
            ->values();

        $schools = $branches
            ->groupBy('school_id')
            ->map(function ($schoolBranches) {
                $school = $schoolBranches->first()?->school;

                if (!$school) {
                    return null;
                }

                return [
                    'id' => $school->id,
                    'name' => $school->name_en,
                    'ar_name' => $school->name_ar,
                    'slug' => $school->slug,
                    'logo' => $this->support->toPublicUrl($school->logo_url),
                    'city_ids' => $schoolBranches->pluck('city_id')->unique()->values()->all(),
                    'country_ids' => $schoolBranches
                        ->pluck('city.country_id')
                        ->filter()
                        ->unique()
                        ->values()
                        ->all(),
                ];
            })
            ->filter()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        $cities = $branches
            ->groupBy('city_id')
            ->map(function ($cityBranches) {
                $city = $cityBranches->first()?->city;
                $country = $city?->country;

                if (!$city || !$country) {
                    return null;
                }

                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'ar_name' => $city->ar_name,
                    'slug' => $city->slug,
                    'country_id' => $country->id,
                    'country_name' => $country->name,
                    'country_ar_name' => $country->ar_name,
                    'flag' => $this->support->toPublicUrl($country->flag),
                ];
            })
            ->filter()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        $countries = $branches
            ->map(fn ($branch) => $branch->city?->country)
            ->filter()
            ->unique('id')
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'ar_name' => $country->ar_name,
                'slug' => $country->slug,
                'flag' => $this->support->toPublicUrl($country->flag),
            ])
            ->all();

        $courseTypes = $courses
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('name_en', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name_en,
                'ar_name' => $category->name_ar,
                'slug' => $category->slug,
            ])
            ->all();

        return response()->json([
            'hero' => [
                'search_data' => [
                    'schools' => $schools,
                    'countries' => $countries,
                    'cities' => $cities,
                    'course_types' => $courseTypes,
                ],
            ],
        ]);
    }
}
