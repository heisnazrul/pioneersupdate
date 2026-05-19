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
        $discounts = LanguageSchoolPioneersDiscount::query()
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-school-pioneers-discounts.index', [
            'discounts' => $discounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.language-school-pioneers-discounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolPioneersDiscount::create($data);

        return redirect()
            ->route('admin.language-school-pioneers-discounts.index')
            ->with('success', 'Pioneers discount created successfully.');
    }

    public function edit(LanguageSchoolPioneersDiscount $languageSchoolPioneersDiscount): View
    {
        return view('admin.language-school-pioneers-discounts.edit', [
            'pioneersDiscount' => $languageSchoolPioneersDiscount,
        ]);
    }

    public function update(Request $request, LanguageSchoolPioneersDiscount $languageSchoolPioneersDiscount): RedirectResponse
    {
        $data = $this->validateData($request);
        $languageSchoolPioneersDiscount->update($data);

        return redirect()
            ->route('admin.language-school-pioneers-discounts.index')
            ->with('success', 'Pioneers discount updated successfully.');
    }

    public function destroy(LanguageSchoolPioneersDiscount $languageSchoolPioneersDiscount): RedirectResponse
    {
        $languageSchoolPioneersDiscount->delete();

        return redirect()
            ->route('admin.language-school-pioneers-discounts.index')
            ->with('success', 'Pioneers discount deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'weeks' => ['required', 'integer', 'min:1'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_full_for' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
