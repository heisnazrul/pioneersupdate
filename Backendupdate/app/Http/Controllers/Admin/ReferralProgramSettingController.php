<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralProgramSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferralProgramSettingController extends Controller
{
    public function index(): View
    {
        $settings = ReferralProgramSetting::query()
            ->orderBy('scope')
            ->get()
            ->keyBy('scope');

        return view('admin.referral-settings.index', compact('settings'));
    }

    public function edit(string $scope): View
    {
        $setting = $this->findSetting($scope);

        return view('admin.referral-settings.edit', compact('setting', 'scope'));
    }

    public function update(Request $request, string $scope): RedirectResponse
    {
        $setting = $this->findSetting($scope);

        $data = $request->validate([
            'discount_type' => ['required', 'in:percent,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'commission_type' => ['required', 'in:percent,fixed'],
            'commission_value' => ['required', 'numeric', 'min:0'],
            'min_booking_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'max_commission_amount' => ['nullable', 'numeric', 'min:0'],
            'cookie_ttl_days' => ['required', 'integer', 'min:1', 'max:365'],
            'attribution_window_days' => ['required', 'integer', 'min:1', 'max:730'],
            'applies_to' => ['nullable', 'array'],
            'applies_to.*' => ['string', 'in:language_courses,online_courses'],
            'is_active' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['applies_to'] = $data['applies_to'] ?? [];

        $setting->update($data);

        return redirect()->route('admin.referral-settings.index')
            ->with('success', ucfirst($scope) . ' referral program settings updated.');
    }

    private function findSetting(string $scope): ReferralProgramSetting
    {
        if (! in_array($scope, ['student', 'agent'], true)) {
            abort(404);
        }

        return ReferralProgramSetting::query()->firstOrCreate(
            ['scope' => $scope],
            [
                'discount_type' => 'percent',
                'discount_value' => 5,
                'commission_type' => 'percent',
                'commission_value' => 3,
                'cookie_ttl_days' => 30,
                'attribution_window_days' => 365,
                'applies_to' => ['language_courses', 'online_courses'],
                'is_active' => true,
            ]
        );
    }
}
