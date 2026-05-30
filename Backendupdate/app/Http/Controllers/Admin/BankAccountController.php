<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    public function index(): View
    {
        $accounts = BankAccount::query()
            ->orderBy('sort_order')
            ->orderBy('name_en')
            ->paginate(20);

        return view('admin.bank-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('admin.bank-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('bank-logos', 'public');
        }

        unset($data['logo']);
        BankAccount::create($data);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account created.');
    }

    public function edit(BankAccount $bankAccount): View
    {
        return view('admin.bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('logo')) {
            if ($bankAccount->logo_path) {
                Storage::disk('public')->delete($bankAccount->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('bank-logos', 'public');
        }

        unset($data['logo']);
        $bankAccount->update($data);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account updated.');
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        if ($bankAccount->logo_path) {
            Storage::disk('public')->delete($bankAccount->logo_path);
        }

        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name_en'         => 'required|string|max:255',
            'name_ar'         => 'nullable|string|max:255',
            'logo'            => 'nullable|image|max:2048',
            'logo_text'       => 'nullable|string|max:255',
            'beneficiary_en'  => 'required|string|max:255',
            'beneficiary_ar'  => 'nullable|string|max:255',
            'account_number'  => 'required|string|max:64',
            'iban'            => 'nullable|string|max:64',
            'sort_order'      => 'nullable|integer|min:0|max:65535',
            'is_active'       => 'required|in:yes,no',
        ]);
    }
}
