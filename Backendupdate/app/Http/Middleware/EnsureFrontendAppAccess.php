<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureFrontendAppAccess
{
    public function handle(Request $request, Closure $next, string $app): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            throw new AccessDeniedHttpException('Unauthenticated.');
        }

        $token = $user->currentAccessToken();

        if (!$user->isFrontendUser()) {
            throw new AccessDeniedHttpException('This account cannot access frontend applications.');
        }

        if (!$user->hasAnyRole(User::APP_ROLE_MAP[$app] ?? [])) {
            throw new AccessDeniedHttpException('This account cannot access the requested application.');
        }

        if ($token && !$token->can('app:' . $app)) {
            throw new AccessDeniedHttpException('This token cannot access the requested application.');
        }

        return $next($request);
    }
}
