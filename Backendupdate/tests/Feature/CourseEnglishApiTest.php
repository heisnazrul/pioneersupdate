<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseEnglishApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_courseenglish_static_and_collection_endpoints_respond(): void
    {
        $this->getJson('/api/courseenglish/about')
            ->assertOk()
            ->assertJsonStructure(['content', 'ar_content']);

        $this->getJson('/api/courseenglish/contact-us')
            ->assertOk()
            ->assertJsonStructure(['content', 'ar_content']);

        $this->getJson('/api/courseenglish/utilities')
            ->assertOk()
            ->assertJsonStructure(['schools', 'countries', 'cities', 'language_course_types']);
    }

    public function test_courseenglish_user_can_manage_wishlist_for_online_course(): void
    {
        $user = User::factory()->create([
            'role' => 'lg_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_student');

        $school = LanguageSchool::create([
            'name_en' => 'Test School',
            'name_ar' => 'معهد تجريبي',
            'slug' => 'test-school',
            'status' => 'active',
        ]);

        $category = LanguageCourseCategory::create([
            'name_en' => 'General English',
            'name_ar' => 'الإنجليزية العامة',
            'slug' => 'general-english',
            'is_active' => 'yes',
        ]);

        $course = LanguageOnlineCourse::create([
            'slug' => 'test-online-course',
            'language_school_id' => $school->id,
            'course_type_id' => $category->id,
            'name' => 'Online English',
            'ar_name' => 'إنجليزي أونلاين',
            'fee_type' => 'weekly',
            'fee_amount' => 100,
            'visible' => true,
            'status' => 'published',
        ]);

        Sanctum::actingAs($user, ['app:courseenglish']);

        $this->postJson('/api/courseenglish/wishlist/add', [
            'course_type' => 'online_courses',
            'course_id' => $course->id,
        ])->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson('/api/courseenglish/wishlist')
            ->assertOk()
            ->assertJsonCount(1, 'items')
            ->assertJsonPath('items.0.course_type', 'online_courses')
            ->assertJsonPath('items.0.course.id', $course->id);
    }

    public function test_courseenglish_user_can_manage_compare_for_language_course_with_weeks(): void
    {
        $user = User::factory()->create([
            'role' => 'lg_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_student');

        $country = Country::create([
            'name' => 'United Kingdom',
            'ar_name' => 'المملكة المتحدة',
            'slug' => 'united-kingdom',
            'flag' => 'flags/uk.svg',
            'country_code' => 'GB',
            'currency_code' => 'GBP',
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
            'name_en' => 'Compare School',
            'name_ar' => 'مدرسة المقارنة',
            'slug' => 'compare-school',
            'status' => 'active',
        ]);

        $branch = LanguageSchoolBranch::create([
            'school_id' => $school->id,
            'city_id' => $city->id,
            'slug' => 'compare-london',
            'is_active' => 'yes',
        ]);

        $category = LanguageCourseCategory::create([
            'name_en' => 'General English',
            'name_ar' => 'الإنجليزية العامة',
            'slug' => 'general-english',
            'is_active' => 'yes',
        ]);

        $course = LanguageSchoolCourse::create([
            'branch_id' => $branch->id,
            'course_category_id' => $category->id,
            'course_name_from_school' => 'Compare English',
            'course_name_from_school_ar' => 'إنجليزي للمقارنة',
            'slug' => 'compare-english',
            'weekly_fee_1' => 120,
            'week_category_1' => 1,
            'is_active' => 'yes',
        ]);

        Sanctum::actingAs($user, ['app:courseenglish']);

        $this->postJson('/api/courseenglish/compare/add', [
            'course_type' => 'language_courses',
            'course_id' => $course->id,
            'weeks' => 8,
        ])->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson('/api/courseenglish/compare')
            ->assertOk()
            ->assertJsonCount(1, 'items')
            ->assertJsonPath('items.0.course_type', 'language_courses')
            ->assertJsonPath('items.0.course_id', $course->id)
            ->assertJsonPath('items.0.weeks', 8);

        $this->postJson('/api/courseenglish/compare/add', [
            'course_type' => 'language_courses',
            'course_id' => $course->id,
            'weeks' => 12,
        ])->assertOk();

        $this->assertDatabaseHas('language_course_compares', [
            'user_id' => $user->id,
            'course_type' => 'language_courses',
            'course_id' => $course->id,
            'weeks' => 12,
        ]);

        $this->postJson('/api/courseenglish/compare/remove', [
            'course_type' => 'language_courses',
            'course_id' => $course->id,
        ])->assertOk();

        $this->getJson('/api/courseenglish/compare')
            ->assertOk()
            ->assertJsonCount(0, 'items');
    }

    public function test_booking_otp_flow_creates_courseenglish_lead_and_frontend_user(): void
    {
        config(['app.debug' => true]);

        $otpResponse = $this->postJson('/api/courseenglish/booking/send-otp', [
            'phone' => '+966500000000',
        ])->assertOk();

        $otp = $otpResponse->json('debug_otp');

        $verificationToken = $this->postJson('/api/courseenglish/booking/verify-otp', [
            'phone' => '+966500000000',
            'otp' => $otp,
        ])->assertOk()->json('verification_token');

        $this->postJson('/api/courseenglish/booking/online-course', [
            'verification_token' => $verificationToken,
            'user_data' => [
                'name' => 'Booking Student',
                'email' => 'booking@example.com',
            ],
            'booking_data' => [
                'course_id' => 123,
                'weeks' => 4,
                'start_date' => '2026-06-01',
                'final_price' => 1200,
                'currency' => 'GBP',
                'supplements_ids' => [],
            ],
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Booking request received successfully.');

        $user = User::where('email', 'booking@example.com')->firstOrFail();

        $this->assertTrue($user->hasRole('lg_student'));
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => Role::where('slug', 'lg_student')->value('id'),
        ]);
    }
}
