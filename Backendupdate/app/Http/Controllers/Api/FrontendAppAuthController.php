<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrontendAppAuthController extends AuthController
{
    public function registerForCourseEnglish(Request $request): JsonResponse
    {
        return $this->registerForApp($request, User::APP_COURSEENGLISH, 'lg_student');
    }

    public function registerForUniversity(Request $request): JsonResponse
    {
        return $this->registerForApp($request, User::APP_UNIVERSITY, 'uni_student');
    }

    public function loginForCourseEnglish(Request $request): JsonResponse
    {
        return $this->loginForApp($request, User::APP_COURSEENGLISH);
    }

    public function loginForUniversity(Request $request): JsonResponse
    {
        return $this->loginForApp($request, User::APP_UNIVERSITY);
    }

    public function meForCourseEnglish(Request $request): JsonResponse
    {
        return $this->meForApp($request, User::APP_COURSEENGLISH);
    }

    public function meForUniversity(Request $request): JsonResponse
    {
        return $this->meForApp($request, User::APP_UNIVERSITY);
    }

    public function logoutForCourseEnglish(Request $request): JsonResponse
    {
        return $this->logoutForApp($request, User::APP_COURSEENGLISH);
    }

    public function logoutForUniversity(Request $request): JsonResponse
    {
        return $this->logoutForApp($request, User::APP_UNIVERSITY);
    }

    public function currentCourseEnglishUser(Request $request): JsonResponse
    {
        return $this->currentUserForApp($request, User::APP_COURSEENGLISH);
    }

    public function currentUniversityUser(Request $request): JsonResponse
    {
        return $this->currentUserForApp($request, User::APP_UNIVERSITY);
    }
}
