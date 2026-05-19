<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversionFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversionFeeController extends Controller
{
    public function index(): View
    {
        $fees = ConversionFee::orderBy('base_currency')->orderBy('target_currency')
            ->paginate(20)->withQueryString();

        return view('admin.conversion-fees.index', compact('fees'));
    }

    public function create(): View
    {
        return view('admin.conversion-fees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        ConversionFee::create($data);

        return redirect()->route('admin.conversion-fees.index')
            ->with('success', 'Conversion fee created.');
    }

    public function edit(ConversionFee $conversionFee): View
    {
        return view('admin.conversion-fees.edit', ['fee' => $conversionFee]);
    }

    public function update(Request $request, ConversionFee $conversionFee): RedirectResponse
    {
        $data = $this->validateData($request, $conversionFee);
        $conversionFee->update($data);

        return redirect()->route('admin.conversion-fees.index')
            ->with('success', 'Conversion fee updated.');
    }

    public function destroy(ConversionFee $conversionFee): RedirectResponse
    {
        $conversionFee->delete();

        return redirect()->route('admin.conversion-fees.index')
            ->with('success', 'Conversion fee deleted.');
    }

    private function validateData(Request $request, ?ConversionFee $fee = null): array
    {
        return $request->validate([
            'base_currency' => ['required', 'string', 'size:3'],
            'target_currency' => ['required', 'string', 'size:3'],
            'fee' => ['required', 'numeric', 'min:0'],
        ]);
    }
}
