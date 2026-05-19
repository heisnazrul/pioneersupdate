<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\UniApplication;
use App\Models\UniversityWishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UniversityStudentApiController extends Controller
{
    public function storeApplication(Request $request): JsonResponse
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'lastName' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
        ]);

        $applicationId = mt_rand(1000, 9999).'0042';
        Application::create([
            'application_id' => $applicationId,
            'user_id' => $request->user('api')?->id,
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'citizenship' => $request->input('citizenship'),
            'nationality' => $request->input('nationality'),
            'nationality_other' => $request->input('nationalityOther'),
            'highest_education' => $request->input('highestEducation'),
            'grade_average' => $request->input('gradeAverage'),
            'has_english_test' => (bool) $request->input('hasEnglishTest', false),
            'english_test_type' => $request->input('englishTestType'),
            'english_test_score' => $request->input('englishTestScore'),
            'destination_interest' => $request->input('destinationInterest', []),
            'destinations_other' => $request->input('destinationsOther'),
            'preferred_intake' => $request->input('preferredIntake'),
            'budget_range' => $request->input('budgetRange'),
            'status' => 'submitted',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully.',
            'application_id' => $applicationId,
        ], 201);
    }

    public function myApplications(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $generalApps = Application::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('email', $user->email);
        })->get()->map(fn ($app) => [
            'id' => $app->id,
            'application_id' => $app->application_id,
            'first_name' => $app->first_name,
            'last_name' => $app->last_name,
            'highest_education' => $app->highest_education,
            'status' => $app->status,
            'created_at' => $app->created_at,
            'destination_interest' => $app->destination_interest ?? [],
            'type' => 'general',
        ]);

        $uniApps = UniApplication::with(['course.university'])->where('email', $user->email)->get()->map(function ($app) {
            $courseName = $app->course?->name ?? $app->course?->ar_name ?? 'University Course';
            $universityName = $app->course?->university?->name ?? '';
            $location = $app->course?->university?->country?->name ?? 'Global';
            return [
                'id' => 'u-'.$app->id,
                'application_id' => 'UNI-'.str_pad((string) $app->id, 4, '0', STR_PAD_LEFT),
                'first_name' => explode(' ', (string) $app->name)[0] ?? '',
                'last_name' => explode(' ', (string) $app->name)[1] ?? '',
                'highest_education' => trim($courseName.($universityName ? " at {$universityName}" : '')),
                'status' => $app->status ?? 'submitted',
                'created_at' => $app->created_at,
                'destination_interest' => [is_string($location) ? $location : 'Global'],
                'type' => 'university',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $generalApps->merge($uniApps)->sortByDesc('created_at')->values(),
        ]);
    }

    public function storeUniApplication(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'intake' => ['required', 'string'],
            'course_id' => ['nullable', 'exists:university_courses,id'],
        ]);

        $application = UniApplication::create($data);

        return response()->json([
            'message' => 'Application submitted successfully',
            'data' => $application,
        ], 201);
    }

    public function myUniApplications(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $applications = UniApplication::with('course.university')
            ->where('email', $user->email)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $applications,
        ]);
    }

    public function wishlist(Request $request): JsonResponse
    {
        $wishlist = UniversityWishlist::where('user_id', $request->user()->id)
            ->with(['course.university'])
            ->get();
        return response()->json($wishlist);
    }

    public function addWishlist(Request $request): JsonResponse
    {
        $request->validate([
            'course_id' => ['required', 'exists:university_courses,id'],
        ]);

        $exists = UniversityWishlist::where('user_id', $request->user()->id)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Course already in wishlist'], 409);
        }

        $wishlist = UniversityWishlist::create([
            'user_id' => $request->user()->id,
            'course_id' => $request->course_id,
        ]);

        return response()->json(['message' => 'Course added to wishlist', 'data' => $wishlist], 201);
    }

    public function removeWishlist(Request $request, int $courseId): JsonResponse
    {
        $deleted = UniversityWishlist::where('user_id', $request->user()->id)
            ->where('course_id', $courseId)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Course removed from wishlist']);
        }

        return response()->json(['message' => 'Course not found in wishlist'], 404);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $generalApps = Application::where('email', $user->email)->count();
        $uniApps = UniApplication::where('email', $user->email)->count();
        $savedCourses = UniversityWishlist::where('user_id', $user->id)->count();

        $recentApp = UniApplication::where('email', $user->email)
            ->with(['course.university'])
            ->latest()
            ->first();

        $recentActivity = null;
        if ($recentApp) {
            $universityName = $recentApp->course?->university?->name ?? 'University';
            $recentActivity = [
                'type' => 'application',
                'title' => 'Application to '.$universityName,
                'status' => $recentApp->status,
                'date' => $recentApp->created_at?->diffForHumans(),
            ];
        }

        return response()->json([
            'stats' => [
                'active_applications' => $generalApps + $uniApps,
                'saved_courses' => $savedCourses,
                'messages' => 0,
            ],
            'recent_activity' => $recentActivity,
            'user' => $user->load('profile'),
        ]);
    }
}
