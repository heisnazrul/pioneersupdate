<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BathroomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BathroomTypeController extends Controller
{
    public function index(): View
    {
        $bathroomTypes = BathroomType::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bathroom-types.index', [
            'bathroomTypes' => $bathroomTypes,
        ]);
    }

    public function create(): View
    {
        return view('admin.bathroom-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        BathroomType::create($data);

        return redirect()
            ->route('admin.bathroom-types.index')
            ->with('success', 'Bathroom type created successfully.');
    }

    public function edit(BathroomType $bathroomType): View
    {
        return view('admin.bathroom-types.edit', [
            'bathroomType' => $bathroomType,
        ]);
    }

    public function update(Request $request, BathroomType $bathroomType): RedirectResponse
    {
        $data = $this->validateData($request, $bathroomType);

        $bathroomType->update($data);

        return redirect()
            ->route('admin.bathroom-types.index')
            ->with('success', 'Bathroom type updated successfully.');
    }

    public function destroy(BathroomType $bathroomType): RedirectResponse
    {
        $bathroomType->delete();

        return redirect()
            ->route('admin.bathroom-types.index')
            ->with('success', 'Bathroom type deleted successfully.');
    }

    private function validateData(Request $request, ?BathroomType $bathroomType = null): array
    {
        return $request->validate([
            'bathroom_code' => ['required', 'string', 'max:255', Rule::unique('bathroom_types', 'bathroom_code')->ignore($bathroomType?->id)],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
        ]);
    }
}
