<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CourseSatLanguageCourseBookingTest extends TestCase
{
    use RefreshDatabase;

    private function seedCourseFixture(): LanguageSchoolCourse
    {
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

        return LanguageSchoolCourse::create([
            'branch_id' => $branch->id,
            'course_category_id' => $category->id,
            'course_name_from_school' => 'Alpha General English',
            'course_name_from_school_ar' => 'ألفا الإنجليزية العامة',
            'slug' => 'alpha-general-english',
            'weekly_fee_1' => 150,
            'week_category_1' => 1,
            'registration_admin_fee' => 50,
            'material_books_fee' => 25,
            'is_active' => 'yes',
        ]);
    }

    public function test_guest_can_register_and_create_language_course_booking(): void
    {
        $course = $this->seedCourseFixture();

        $response = $this->postJson('/api/coursesat/bookings/language-course', [
            'selection' => [
                'course_id' => $course->id,
                'weeks' => 4,
                'start_date' => '2026-06-08',
                'accommodation_id' => 'no-acc',
            ],
            'display_currency' => 'GBP',
            'user_data' => [
                'name' => 'Guest Student',
                'email' => 'guest-booking@example.com',
                'phone' => '+966512345678',
                'password' => 'password123',
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['reference_no', 'booking_id', 'token', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'guest-booking@example.com',
            'role' => 'lg_student',
        ]);

        $this->assertDatabaseHas('language_course_bookings', [
            'course_id' => $course->id,
            'weeks' => 4,
            'display_currency' => 'GBP',
            'contact_email' => 'guest-booking@example.com',
            'status' => 'pending',
            'source' => 'coursesat',
        ]);
    }

    public function test_logged_in_user_can_create_language_course_booking(): void
    {
        $course = $this->seedCourseFixture();

        $user = User::factory()->create([
            'email' => 'loggedin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'lg_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_student');

        $token = $user->createToken('test', ['app:courseenglish', 'role:lg_student'])->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/coursesat/bookings/language-course', [
            'selection' => [
                'course_id' => $course->id,
                'weeks' => 2,
                'start_date' => '2026-07-01',
            ],
            'display_currency' => 'GBP',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonMissing(['token']);

        $this->assertDatabaseHas('language_course_bookings', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'weeks' => 2,
        ]);
    }

    public function test_guest_booking_rejects_existing_email(): void
    {
        $course = $this->seedCourseFixture();

        User::factory()->create([
            'email' => 'exists@example.com',
            'role' => 'lg_student',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/coursesat/bookings/language-course', [
            'selection' => [
                'course_id' => $course->id,
                'weeks' => 2,
                'start_date' => '2026-07-01',
            ],
            'display_currency' => 'GBP',
            'user_data' => [
                'name' => 'Another User',
                'email' => 'exists@example.com',
                'phone' => '+966500000000',
                'password' => 'password123',
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_data.email']);
    }
}
