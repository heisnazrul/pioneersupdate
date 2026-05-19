<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\LanguageCourseType;
use App\Models\LanguageSchool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CourseEnglishUtilitiesController extends Controller
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

    public function index(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_utilities_index_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $schools = LanguageSchool::query()
                ->select('name', 'ar_name', 'logo', 'slug')
                ->whereHas('branches.courses')
                ->orderBy('name')
                ->get()
                ->map(function (LanguageSchool $school) {
                    return [
                        'name' => $school->name,
                        'ar_name' => $school->ar_name,
                        'slug' => $school->slug,
                        'logo' => $this->toPublicUrl($school->logo),
                    ];
                })
                ->values();

            $countries = Country::query()
                ->select('name', 'ar_name', 'flag', 'slug')
                ->whereHas('languageSchoolBranches.courses')
                ->orderBy('name')
                ->get()
                ->map(function (Country $country) {
                    return [
                        'name' => $country->name,
                        'ar_name' => $country->ar_name,
                        'slug' => $country->slug,
                        'flag' => $this->toPublicUrl($country->flag),
                    ];
                })
                ->values();

            $cities = City::query()
                ->select('name', 'ar_name', 'country_id', 'slug')
                ->whereHas('languageSchoolBranches.courses')
                ->with(['country:id,name,ar_name,flag'])
                ->orderBy('name')
                ->get()
                ->map(function (City $city) {
                    return [
                        'name' => $city->name,
                        'ar_name' => $city->ar_name,
                        'slug' => $city->slug,
                        'country_name' => $city->country?->name,
                        'country_ar_name' => $city->country?->ar_name,
                        'flag' => $this->toPublicUrl($city->country?->flag),
                    ];
                })
                ->values();

            $languageCourseTypes = LanguageCourseType::query()
                ->select('name', 'ar_name')
                ->whereIn('id', function ($query) {
                    $query->select('language_course_type_id')
                        ->from('language_school_courses')
                        ->distinct();
                })
                ->orderBy('name')
                ->get();

            return [
                'schools' => $schools,
                'countries' => $countries,
                'cities' => $cities,
                'language_course_types' => $languageCourseTypes,
            ];
        });

        return response()->json($response);
    }
}
