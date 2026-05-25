<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseSatApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_coursesat_home_returns_only_active_hero_search_records_with_courses(): void
    {
        $country = Country::create([
            'name' => 'United Kingdom',
            'ar_name' => 'المملكة المتحدة',
            'slug' => 'united-kingdom',
            'flag' => 'flags/uk.svg',
            'country_code' => 'GB',
            'is_active' => true,
        ]);

        $city = City::create([
            'country_id' => $country->id,
            'name' => 'London',
            'ar_name' => 'لندن',
            'slug' => 'london',
            'is_active' => true,
        ]);

        $school = LanguageSchool::create([
            'name_en' => 'Alpha School',
            'name_ar' => 'مدرسة ألفا',
            'slug' => 'alpha-school',
            'logo_url' => 'schools/alpha.png',
            'status' => 'active',
        ]);

        $branch = LanguageSchoolBranch::create([
            'school_id' => $school->id,
            'city_id' => $city->id,
            'slug' => 'alpha-london',
            'is_active' => 'yes',
        ]);

        $category = LanguageCourseCategory::create([
            'name_en' => 'General English',
            'name_ar' => 'الإنجليزية العامة',
            'slug' => 'general-english',
            'is_active' => 'yes',
        ]);

        LanguageSchoolCourse::create([
            'branch_id' => $branch->id,
            'course_category_id' => $category->id,
            'course_name_from_school' => 'Alpha General English',
            'course_name_from_school_ar' => 'ألفا الإنجليزية العامة',
            'slug' => 'alpha-general-english',
            'weekly_fee_1' => 150,
            'is_active' => 'yes',
        ]);

        $inactiveCountry = Country::create([
            'name' => 'France',
            'ar_name' => 'فرنسا',
            'slug' => 'france',
            'flag' => 'flags/fr.svg',
            'country_code' => 'FR',
            'is_active' => false,
        ]);

        $inactiveCountryCity = City::create([
            'country_id' => $inactiveCountry->id,
            'name' => 'Paris',
            'ar_name' => 'باريس',
            'slug' => 'paris',
            'is_active' => true,
        ]);

        $inactiveSchool = LanguageSchool::create([
            'name_en' => 'Inactive School',
            'name_ar' => 'مدرسة غير نشطة',
            'slug' => 'inactive-school',
            'logo_url' => 'schools/inactive.png',
            'status' => 'inactive',
        ]);

        $inactiveBranch = LanguageSchoolBranch::create([
            'school_id' => $inactiveSchool->id,
            'city_id' => $inactiveCountryCity->id,
            'slug' => 'inactive-paris',
            'is_active' => 'yes',
        ]);

        $inactiveCategory = LanguageCourseCategory::create([
            'name_en' => 'Inactive Category',
            'name_ar' => 'فئة غير نشطة',
            'slug' => 'inactive-category',
            'is_active' => 'no',
        ]);

        LanguageSchoolCourse::create([
            'branch_id' => $inactiveBranch->id,
            'course_category_id' => $inactiveCategory->id,
            'course_name_from_school' => 'Inactive Course',
            'course_name_from_school_ar' => 'دورة غير نشطة',
            'slug' => 'inactive-course',
            'weekly_fee_1' => 200,
            'is_active' => 'no',
        ]);

        $emptyCountry = Country::create([
            'name' => 'Canada',
            'ar_name' => 'كندا',
            'slug' => 'canada',
            'flag' => 'flags/ca.svg',
            'country_code' => 'CA',
            'is_active' => true,
        ]);

        City::create([
            'country_id' => $emptyCountry->id,
            'name' => 'Toronto',
            'ar_name' => 'تورونتو',
            'slug' => 'toronto',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/coursesat/home');

        $response->assertOk()
            ->assertJsonStructure([
                'hero' => [
                    'search_data' => [
                        'schools',
                        'countries',
                        'cities',
                        'course_types',
                    ],
                ],
            ])
            ->assertJsonCount(1, 'hero.search_data.schools')
            ->assertJsonCount(1, 'hero.search_data.countries')
            ->assertJsonCount(1, 'hero.search_data.cities')
            ->assertJsonCount(1, 'hero.search_data.course_types')
            ->assertJsonPath('hero.search_data.schools.0.id', $school->id)
            ->assertJsonPath('hero.search_data.schools.0.name', 'Alpha School')
            ->assertJsonPath('hero.search_data.schools.0.logo', url('/storage/schools/alpha.png'))
            ->assertJsonPath('hero.search_data.schools.0.city_ids.0', $city->id)
            ->assertJsonPath('hero.search_data.schools.0.country_ids.0', $country->id)
            ->assertJsonPath('hero.search_data.countries.0.id', $country->id)
            ->assertJsonPath('hero.search_data.countries.0.flag', url('/storage/flags/uk.svg'))
            ->assertJsonPath('hero.search_data.cities.0.id', $city->id)
            ->assertJsonPath('hero.search_data.cities.0.country_id', $country->id)
            ->assertJsonPath('hero.search_data.cities.0.country_name', 'United Kingdom')
            ->assertJsonPath('hero.search_data.cities.0.country_ar_name', 'المملكة المتحدة')
            ->assertJsonPath('hero.search_data.course_types.0.id', $category->id)
            ->assertJsonPath('hero.search_data.course_types.0.name', 'General English');

        $this->assertSame(
            [$school->id],
            collect($response->json('hero.search_data.schools'))->pluck('id')->all()
        );
        $this->assertSame(
            [$country->id],
            collect($response->json('hero.search_data.countries'))->pluck('id')->all()
        );
        $this->assertSame(
            [$city->id],
            collect($response->json('hero.search_data.cities'))->pluck('id')->all()
        );
        $this->assertSame(
            [$category->id],
            collect($response->json('hero.search_data.course_types'))->pluck('id')->all()
        );
    }

    public function test_coursesat_home_returns_empty_search_data_when_no_qualifying_courses_exist(): void
    {
        $response = $this->getJson('/api/coursesat/home');

        $response->assertOk()
            ->assertExactJson([
                'hero' => [
                    'search_data' => [
                        'schools' => [],
                        'countries' => [],
                        'cities' => [],
                        'course_types' => [],
                    ],
                ],
            ]);
    }
}
