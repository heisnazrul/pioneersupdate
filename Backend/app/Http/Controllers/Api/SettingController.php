<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\SystemSettings;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $contact = SystemSettings::contactInfo();
        $branding = SystemSettings::branding();

        // Ensure social links are structured correctly for frontend if needed
        // but frontend expects array of objects {platform, url} which it is.

        return response()->json([
            'contact' => $contact,
            'branding' => $branding,
        ]);
    }
}
