<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FrontendAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_level_user_can_register_for_courseenglish(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Course Student',
            'email' => 'student@example.com',
            'password' => 'password123',
            'app' => 'courseenglish',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('user.email', 'student@example.com')
            ->assertJsonPath('user.role', 'lg_student')
            ->assertJsonPath('access.courseenglish', true)
            ->assertJsonPath('access.university', false);

        $user = User::where('email', 'student@example.com')->firstOrFail();

        $this->assertTrue($user->hasRole('lg_student'));
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => Role::where('slug', 'lg_student')->value('id'),
        ]);
    }

    public function test_high_level_web_only_user_is_rejected_by_api_login(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('admin');

        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
            'app' => 'courseenglish',
        ]);

        $response->assertForbidden()
            ->assertJsonPath('message', 'Something went wrong. Please try again.');
    }

    public function test_multi_role_low_level_user_can_log_into_both_apps(): void
    {
        $user = User::factory()->create([
            'email' => 'multi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'lg_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_student');
        $user->roles()->syncWithoutDetaching([
            Role::where('slug', 'uni_student')->value('id'),
        ]);

        $courseEnglishLogin = $this->postJson('/api/auth/login', [
            'email' => 'multi@example.com',
            'password' => 'password123',
            'app' => 'courseenglish',
        ]);

        $courseEnglishLogin->assertOk()
            ->assertJsonPath('user.role', 'lg_student')
            ->assertJsonPath('access.courseenglish', true)
            ->assertJsonPath('access.university', true);

        $universityLogin = $this->postJson('/api/auth/login', [
            'email' => 'multi@example.com',
            'password' => 'password123',
            'app' => 'university',
        ]);

        $universityLogin->assertOk()
            ->assertJsonPath('user.role', 'uni_student')
            ->assertJsonPath('access.courseenglish', true)
            ->assertJsonPath('access.university', true);
    }

    public function test_me_and_logout_work_with_sanctum_tokens(): void
    {
        $user = User::factory()->create([
            'email' => 'me@example.com',
            'password' => Hash::make('password123'),
            'role' => 'uni_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('uni_student');

        $login = $this->postJson('/api/auth/login', [
            'email' => 'me@example.com',
            'password' => 'password123',
            'app' => 'university',
        ])->assertOk();

        $token = $login->json('access_token');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/me?app=university')
            ->assertOk()
            ->assertJsonPath('user.email', 'me@example.com')
            ->assertJsonPath('user.role', 'uni_student');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_courseenglish_namespaced_login_route_issues_courseenglish_role(): void
    {
        $user = User::factory()->create([
            'email' => 'ce-agent@example.com',
            'password' => Hash::make('password123'),
            'role' => 'lg_agent',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_agent');

        $this->postJson('/api/courseenglish/auth/login', [
            'email' => 'ce-agent@example.com',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonPath('app', 'courseenglish')
            ->assertJsonPath('user.role', 'lg_agent')
            ->assertJsonPath('access.courseenglish', true)
            ->assertJsonPath('access.university', false);
    }

    public function test_courseenglish_token_cannot_access_university_namespaced_me_route(): void
    {
        $user = User::factory()->create([
            'email' => 'scoped@example.com',
            'password' => Hash::make('password123'),
            'role' => 'lg_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_student');
        $user->roles()->syncWithoutDetaching([
            Role::where('slug', 'uni_student')->value('id'),
        ]);

        $token = $this->postJson('/api/courseenglish/auth/login', [
            'email' => 'scoped@example.com',
            'password' => 'password123',
        ])->assertOk()->json('access_token');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/university/auth/me')
            ->assertForbidden()
            ->assertJsonPath('message', 'This token cannot access the requested application.');
    }

    public function test_failed_login_is_rate_limited(): void
    {
        $user = User::factory()->create([
            'email' => 'throttle@example.com',
            'password' => Hash::make('password123'),
            'role' => 'lg_student',
            'status' => 'active',
        ]);
        $user->assignPrimaryRole('lg_student');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/auth/login', [
                'email' => 'throttle@example.com',
                'password' => 'wrong-password',
                'app' => 'courseenglish',
            ])->assertUnauthorized();
        }

        $this->postJson('/api/auth/login', [
            'email' => 'throttle@example.com',
            'password' => 'wrong-password',
            'app' => 'courseenglish',
        ])->assertStatus(429);
    }
}
