<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolPioneersDiscount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolPioneersDiscountController extends Controller
{
    public function index(): View
    {
        $discounts = LanguageSchoolPioneersDiscount::orderBy('weeks')->paginate(20);

        return view('admin.language-school-pioneers-discounts.index', compact('discounts'));
    }

    public function create(): View
    {
        return view('admin.language-school-pioneers-discounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['is_active'] = $request->boolean('is_active', true);

        LanguageSchoolPioneersDiscount::create($data);

        return redirect()->route('admin.language-school-pioneers-discounts.index')
            ->with('success', 'Pioneers discount created successfully.');
    }

    public function edit(LanguageSchoolPioneersDiscount $pioneers_discount): View
    {
        $languageSchoolPioneersDiscount = $pioneers_discount;

        return view('admin.language-school-pioneers-discounts.edit', compact('languageSchoolPioneersDiscount'));
    }

    public function update(Request $request, LanguageSchoolPioneersDiscount $pioneers_discount): RedirectResponse
    {
        $languageSchoolPioneersDiscount = $pioneers_discount;
        $data = $this->validateRequest($request);
        $data['is_active'] = $request->boolean('is_active');

        $languageSchoolPioneersDiscount->update($data);

        return redirect()->route('admin.language-school-pioneers-discounts.index')
            ->with('success', 'Pioneers discount updated successfully.');
    }

    public function destroy(LanguageSchoolPioneersDiscount $pioneers_discount): RedirectResponse
    {
        $languageSchoolPioneersDiscount = $pioneers_discount;
        $languageSchoolPioneersDiscount->delete();

        return redirect()->route('admin.language-school-pioneers-discounts.index')
            ->with('success', 'Pioneers discount deleted.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'weeks' => ['required', 'integer', 'min:1'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_full_for' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
