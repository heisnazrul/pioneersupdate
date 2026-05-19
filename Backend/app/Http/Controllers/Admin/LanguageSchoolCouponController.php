<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolCoupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageSchoolCouponController extends Controller
{
    public function index(): View
    {
        $coupons = LanguageSchoolCoupon::query()
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-school-coupons.index', [
            'coupons' => $coupons,
        ]);
    }

    public function create(): View
    {
        return view('admin.language-school-coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['used_count'] = $data['used_count'] ?? 0;

        LanguageSchoolCoupon::create($data);

        return redirect()
            ->route('admin.language-school-coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(LanguageSchoolCoupon $languageSchoolCoupon): View
    {
        return view('admin.language-school-coupons.edit', [
            'coupon' => $languageSchoolCoupon,
        ]);
    }

    public function update(Request $request, LanguageSchoolCoupon $languageSchoolCoupon): RedirectResponse
    {
        $data = $this->validateData($request, $languageSchoolCoupon);

        $languageSchoolCoupon->update($data);

        return redirect()
            ->route('admin.language-school-coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(LanguageSchoolCoupon $languageSchoolCoupon): RedirectResponse
    {
        $languageSchoolCoupon->delete();

        return redirect()
            ->route('admin.language-school-coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    private function validateData(Request $request, ?LanguageSchoolCoupon $languageSchoolCoupon = null): array
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('language_school_coupons', 'code')->ignore($languageSchoolCoupon),
            ],
            'name' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', Rule::in(['percent', 'flat'])],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'usage_limit' => ['required', 'integer', 'min:1'],
            'used_count' => ['nullable', 'integer', 'min:0'],
            'expiration_date' => ['nullable', 'date'],
            'minimum_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['used_count'] = $request->input('used_count', $languageSchoolCoupon?->used_count ?? 0) ?? 0;

        return $data;
    }
}
