<?php

namespace Tests\Feature;

use App\Models\BookingPaymentEvent;
use App\Models\City;
use App\Models\Country;
use App\Models\LanguageCourseBooking;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\Role;
use App\Models\User;
use App\Support\BookingStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffPanelTest extends TestCase
{
    use RefreshDatabase;

    private function staffUser(string $role): User
    {
        $user = User::factory()->create([
            'role' => $role,
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);
        $user->assignPrimaryRole($role);

        return $user;
    }

    private function seedBookingFor(User $student, ?int $assignedTo = null): LanguageCourseBooking
    {
        $country = Country::create([
            'name' => 'United Kingdom',
            'ar_name' => 'UK',
            'slug' => 'uk-staff',
            'flag' => 'flags/uk.svg',
            'country_code' => 'GB',
            'currency_code' => 'GBP',
            'is_active' => true,
        ]);

        $city = City::create([
            'country_id' => $country->id,
            'name' => 'London',
            'ar_name' => 'London',
            'slug' => 'london-staff',
            'is_active' => true,
        ]);

        $school = LanguageSchool::create([
            'name_en' => 'Staff Test School',
            'name_ar' => 'Staff',
            'slug' => 'staff-test-school',
            'logo_url' => 'schools/test.png',
            'status' => 'active',
        ]);

        $branch = LanguageSchoolBranch::create([
            'school_id' => $school->id,
            'city_id' => $city->id,
            'slug' => 'staff-branch',
            'is_active' => 'yes',
        ]);

        $category = LanguageCourseCategory::create([
            'name_en' => 'General',
            'name_ar' => 'General',
            'slug' => 'general-staff',
            'is_active' => 'yes',
        ]);

        $course = LanguageSchoolCourse::create([
            'branch_id' => $branch->id,
            'course_category_id' => $category->id,
            'course_name_from_school' => 'Test Course',
            'course_name_from_school_ar' => 'Test',
            'slug' => 'staff-test-course',
            'weekly_fee_1' => 100,
            'week_category_1' => 1,
            'registration_admin_fee' => 50,
            'is_active' => 'yes',
        ]);

        return LanguageCourseBooking::create([
            'reference_no' => 'LC-STAFF-' . uniqid(),
            'user_id' => $student->id,
            'language_school_id' => $school->id,
            'course_id' => $course->id,
            'status' => BookingStatus::PENDING,
            'source' => 'test',
            'assigned_to' => $assignedTo,
            'contact_name' => $student->name,
            'contact_email' => $student->email,
            'contact_phone' => '+966500000000',
            'weeks' => 2,
            'start_date' => '2026-07-01',
            'subtotal' => 500,
            'total_amount' => 500,
            'display_currency' => 'SAR',
            'pricing_snapshot' => ['total' => 500],
        ]);
    }

    public function test_login_redirects_staff_roles_to_correct_panel(): void
    {
        foreach (['counsellor' => 'counsellor.dashboard', 'team' => 'team.dashboard', 'admin' => 'admin.dashboard'] as $role => $route) {
            $user = $this->staffUser($role);

            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'password123',
            ]);

            $response->assertRedirect(route($route));
        }
    }

    public function test_counsellor_cannot_access_team_panel(): void
    {
        $counsellor = $this->staffUser('counsellor');

        $this->actingAs($counsellor)
            ->get('/team')
            ->assertForbidden();
    }

    public function test_counsellor_cannot_view_unassigned_booking(): void
    {
        $counsellor = $this->staffUser('counsellor');
        $other = $this->staffUser('counsellor');
        $student = User::factory()->create(['role' => 'lg_student', 'status' => 'active']);
        $student->assignPrimaryRole('lg_student');

        $booking = $this->seedBookingFor($student, $other->id);

        $this->actingAs($counsellor)
            ->get(route('counsellor.bookings.show', ['type' => 'language_course', 'id' => $booking->id]))
            ->assertForbidden();
    }

    public function test_team_can_reassign_booking_counsellor(): void
    {
        $team = $this->staffUser('team');
        $counsellor = $this->staffUser('counsellor');
        $student = User::factory()->create(['role' => 'lg_student', 'status' => 'active']);
        $student->assignPrimaryRole('lg_student');

        $booking = $this->seedBookingFor($student, null);

        $this->actingAs($team)
            ->post(route('team.bookings.assign', ['type' => 'language_course', 'id' => $booking->id]), [
                'assigned_to' => $counsellor->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('language_course_bookings', [
            'id' => $booking->id,
            'assigned_to' => $counsellor->id,
        ]);
    }

    public function test_payment_status_transition_creates_audit_event(): void
    {
        $counsellor = $this->staffUser('counsellor');
        $student = User::factory()->create(['role' => 'lg_student', 'status' => 'active']);
        $student->assignPrimaryRole('lg_student');

        $booking = $this->seedBookingFor($student, $counsellor->id);
        $booking->update(['status' => BookingStatus::CONFIRMED]);

        $this->actingAs($counsellor)
            ->post(route('counsellor.bookings.status', ['type' => 'language_course', 'id' => $booking->id]), [
                'status' => BookingStatus::PAID,
                'payment_reference' => 'TRX-123',
                'payment_method' => 'bank_transfer',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('language_course_bookings', [
            'id' => $booking->id,
            'status' => BookingStatus::PAID,
            'payment_reference' => 'TRX-123',
        ]);

        $this->assertDatabaseHas('booking_payment_events', [
            'booking_type' => 'language_course',
            'booking_id' => $booking->id,
            'user_id' => $counsellor->id,
            'old_status' => BookingStatus::CONFIRMED,
            'new_status' => BookingStatus::PAID,
        ]);

        $this->assertSame(1, BookingPaymentEvent::query()->count());
    }
}
