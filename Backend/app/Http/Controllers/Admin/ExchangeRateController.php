<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ExchangeRateController extends Controller
{
    public function index(): View
    {
        $rates = ExchangeRate::orderBy('base_currency')->orderBy('target_currency')
            ->paginate(20)->withQueryString();

        return view('admin.exchange-rates.index', compact('rates'));
    }

    /**
     * Fetch GBP rates for a set of target currencies and upsert.
     * Mirrors legacy controller behavior.
     */
    public function getGbpAllRates(): RedirectResponse
    {
        $baseCurrency = 'GBP';
        $targetCurrencies = [
            'INR', 'CNY', 'JPY', 'PKR', 'BDT', 'ZAR', 'SGD', 'SAR', 'KWD', 'OMR',
            'BHD', 'QAR', 'EGP', 'LYD', 'IRR', 'MYR', 'THB', 'IDR', 'PHP', 'VND',
            'KOR', 'TWD', 'AUD', 'NZD', 'AFN', 'LKR', 'USD', 'EUR', 'CAD', 'CHF', 'AED',
        ];

        // NOTE: key copied from legacy; consider moving to config/env.
        $response = Http::get("https://v6.exchangerate-api.com/v6/b48ba6de70457ee823d7fe7f/latest/{$baseCurrency}");

        if ($response->successful()) {
            $rates = $response->json()['conversion_rates'] ?? [];
            foreach ($targetCurrencies as $targetCurrency) {
                if (isset($rates[$targetCurrency])) {
                    ExchangeRate::updateOrCreate(
                        ['base_currency' => $baseCurrency, 'target_currency' => $targetCurrency],
                        ['rate' => $rates[$targetCurrency]]
                    );
                }
            }

            return redirect()->route('admin.exchange-rates.index')->with('success', 'Rates updated successfully.');
        }

        return redirect()->route('admin.exchange-rates.index')->with('error', 'Failed to fetch rates.');
    }

    public function create(): View
    {
        return view('admin.exchange-rates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        ExchangeRate::create($data);

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', 'Exchange rate created.');
    }

    public function edit(ExchangeRate $exchangeRate): View
    {
        return view('admin.exchange-rates.edit', ['rate' => $exchangeRate]);
    }

    public function update(Request $request, ExchangeRate $exchangeRate): RedirectResponse
    {
        $data = $this->validateData($request, $exchangeRate);
        $exchangeRate->update($data);

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', 'Exchange rate updated.');
    }

    public function destroy(ExchangeRate $exchangeRate): RedirectResponse
    {
        $exchangeRate->delete();

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', 'Exchange rate deleted.');
    }

    private function validateData(Request $request, ?ExchangeRate $rate = null): array
    {
        return $request->validate([
            'base_currency' => ['required', 'string', 'size:3'],
            'target_currency' => ['required', 'string', 'size:3',],
            'rate' => ['required', 'numeric', 'min:0'],
        ]);
    }
}
