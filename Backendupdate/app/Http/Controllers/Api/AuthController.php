<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FrontendAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    public function __construct(
        private readonly FrontendAuthService $frontendAuthService
    ) {
    }

    public function register(Request $request): JsonResponse
    {
        return $this->registerForApp($request, $request->input('app'));
    }

    public function registerForApp(Request $request, ?string $app = null, ?string $defaultRole = null): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['nullable', Rule::in(\App\Models\User::LOW_LEVEL_ROLES)],
            'app' => ['nullable', Rule::in(\App\Models\User::FRONTEND_APPS)],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $data['app'] = $app ?? $data['app'] ?? null;
        $data['role'] = $data['role'] ?? $defaultRole;

        $payload = $this->frontendAuthService->register($data);

        return response()->json($payload, 201);
    }

    public function login(Request $request): JsonResponse
    {
        return $this->loginForApp($request, $request->input('app'));
    }

    public function loginForApp(Request $request, ?string $app = null): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'app' => ['nullable', Rule::in(\App\Models\User::FRONTEND_APPS)],
        ]);

        $credentials['app'] = $app ?? $credentials['app'] ?? null;

        try {
            $payload = $this->frontendAuthService->login($credentials);
        } catch (AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        } catch (AccessDeniedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 403);
        } catch (ValidationException $exception) {
            throw $exception;
        }

        return response()->json($payload);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->meForApp($request, $request->query('app'));
    }

    public function meForApp(Request $request, ?string $app = null): JsonResponse
    {
        return response()->json(
            $this->frontendAuthService->me($request->user(), $app)
        );
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->logoutForApp($request, $request->input('app'));
    }

    public function logoutForApp(Request $request, ?string $app = null): JsonResponse
    {
        if ($app) {
            $this->frontendAuthService->assertAppAccess($request->user(), $app, true);
        }

        $this->frontendAuthService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function currentUser(Request $request): JsonResponse
    {
        return $this->currentUserForApp($request, $request->query('app'));
    }

    public function currentUserForApp(Request $request, ?string $app = null): JsonResponse
    {
        $payload = $this->frontendAuthService->me($request->user(), $app);

        return response()->json($payload['user']);
    }
}
