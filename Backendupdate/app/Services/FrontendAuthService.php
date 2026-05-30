<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use App\Services\Referral\ReferralService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FrontendAuthService
{
    public function __construct(
        private readonly ReferralService $referralService,
    ) {
    }

    public function register(array $data): array
    {
        $app = $this->resolveApp($data['app'] ?? null, $data['role'] ?? null);
        $role = $this->resolveRequestedRole($app, $data['role'] ?? null);

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $this->normalizePhone($data['phone'] ?? null),
            'password' => Hash::make($data['password']),
            'role' => $role,
            'status' => 'active',
        ]);

        $user->assignPrimaryRole($role);

        $this->referralService->ensureStudentReferralCode($user);

        if (! empty($data['referral_code'])) {
            $this->referralService->attributeUser($user->fresh(), (string) $data['referral_code'], 'code');
        }

        return $this->issueFrontendToken($user->fresh('roles'), $app);
    }

    public function login(array $credentials): array
    {
        $user = User::with('roles')->where('email', strtolower($credentials['email']))->first();

        if (!$user || !$user->password || !Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        if ($user->status === 'banned') {
            throw new AccessDeniedHttpException('This account is not available.');
        }

        if ($user->status !== 'active') {
            throw new AccessDeniedHttpException('This account is not available.');
        }

        if (!$user->isFrontendUser()) {
            throw new AccessDeniedHttpException('This account cannot access frontend applications.');
        }

        $app = $this->resolveLoginApp($user, $credentials['app'] ?? null);

        if (!$this->userCanAccessApp($user, $app)) {
            throw new AccessDeniedHttpException('This account cannot access the requested application.');
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return $this->issueFrontendToken($user, $app);
    }

    public function me(User $user, ?string $app = null): array
    {
        $user->loadMissing(['profile', 'roles']);
        $effectiveApp = $app ? $this->resolveApp($app) : $this->inferPreferredApp($user);
        $this->assertAppAccess($user, $effectiveApp, true);

        return $this->buildPayload($user, $effectiveApp);
    }

    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

    public function assertAppAccess(User $user, string $app, bool $requireTokenAbility = false): void
    {
        if (!$user->isFrontendUser()) {
            throw new AccessDeniedHttpException('This account cannot access frontend applications.');
        }

        if (!$this->userCanAccessApp($user, $app)) {
            throw new AccessDeniedHttpException('This account cannot access the requested application.');
        }

        if ($requireTokenAbility) {
            $token = $user->currentAccessToken();

            if ($token && !$token->can('app:' . $app)) {
                throw new AccessDeniedHttpException('This token cannot access the requested application.');
            }
        }
    }

    private function issueFrontendToken(User $user, string $app): array
    {
        $abilities = array_merge(
            ['app:' . $app],
            $user->assignedRoleSlugs()->map(fn (string $role) => 'role:' . $role)->all()
        );

        $token = $user->createToken($app . '-frontend', $abilities);

        return $this->buildPayload($user, $app, $token->plainTextToken);
    }

    private function buildPayload(User $user, string $app, ?string $plainTextToken = null): array
    {
        $roleSlugs = $user->assignedRoleSlugs()->values()->all();
        $primaryRole = $user->primaryFrontendRoleForApp($app)
            ?? ($user->role && in_array($user->role, User::LOW_LEVEL_ROLES, true) ? $user->role : null)
            ?? ($roleSlugs[0] ?? null);

        $userPayload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'role' => $primaryRole,
            'avatar' => $user->avatar,
            'last_login_at' => $user->last_login_at,
            'profile' => $user->profile,
        ];

        $payload = [
            'success' => true,
            'token_type' => 'Bearer',
            'user' => $userPayload,
            'roles' => $roleSlugs,
            'access' => [
                'courseenglish' => $user->canAccessCourseEnglish(),
                'university' => $user->canAccessUniversity(),
            ],
            'app' => $app,
        ];

        if ($plainTextToken) {
            $payload['access_token'] = $plainTextToken;
        }

        return $payload;
    }

    private function resolveRequestedRole(string $app, ?string $role = null): string
    {
        $resolvedRole = $role;

        if (!$resolvedRole) {
            return $app === User::APP_UNIVERSITY ? 'uni_student' : 'lg_student';
        }

        if (!in_array($resolvedRole, User::LOW_LEVEL_ROLES, true)) {
            throw ValidationException::withMessages([
                'role' => ['Only low-level frontend roles may use this endpoint.'],
            ]);
        }

        $allowedRoles = User::APP_ROLE_MAP[$app] ?? [];

        if (!in_array($resolvedRole, $allowedRoles, true)) {
            throw ValidationException::withMessages([
                'role' => ['The selected role is not allowed for this application.'],
            ]);
        }

        return $resolvedRole;
    }

    private function resolveLoginApp(User $user, ?string $requestedApp): string
    {
        if ($requestedApp) {
            return $this->resolveApp($requestedApp);
        }

        return $this->inferPreferredApp($user);
    }

    private function resolveApp(?string $app, ?string $role = null): string
    {
        if ($app && in_array($app, User::FRONTEND_APPS, true)) {
            return $app;
        }

        if ($role) {
            if (in_array($role, User::APP_ROLE_MAP[User::APP_COURSEENGLISH], true)) {
                return User::APP_COURSEENGLISH;
            }

            if (in_array($role, User::APP_ROLE_MAP[User::APP_UNIVERSITY], true)) {
                return User::APP_UNIVERSITY;
            }
        }

        return User::APP_COURSEENGLISH;
    }

    private function inferPreferredApp(User $user): string
    {
        if ($user->role && in_array($user->role, User::APP_ROLE_MAP[User::APP_UNIVERSITY], true)) {
            return User::APP_UNIVERSITY;
        }

        if ($user->role && in_array($user->role, User::APP_ROLE_MAP[User::APP_COURSEENGLISH], true)) {
            return User::APP_COURSEENGLISH;
        }

        if ($user->canAccessCourseEnglish() && !$user->canAccessUniversity()) {
            return User::APP_COURSEENGLISH;
        }

        if ($user->canAccessUniversity() && !$user->canAccessCourseEnglish()) {
            return User::APP_UNIVERSITY;
        }

        if ($user->hasRole('lg_agent') || $user->hasRole('lg_student')) {
            return User::APP_COURSEENGLISH;
        }

        return User::APP_UNIVERSITY;
    }

    private function userCanAccessApp(User $user, string $app): bool
    {
        return match ($app) {
            User::APP_COURSEENGLISH => $user->canAccessCourseEnglish(),
            User::APP_UNIVERSITY => $user->canAccessUniversity(),
            default => false,
        };
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $value = trim($phone);

        return $value !== '' ? $value : null;
    }
}
