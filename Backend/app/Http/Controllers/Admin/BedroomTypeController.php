<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BedroomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BedroomTypeController extends Controller
{
    public function index(): View
    {
        $bedroomTypes = BedroomType::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bedroom-types.index', [
            'bedroomTypes' => $bedroomTypes,
        ]);
    }

    public function create(): View
    {
        return view('admin.bedroom-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        BedroomType::create($data);

        return redirect()
            ->route('admin.bedroom-types.index')
            ->with('success', 'Bedroom type created successfully.');
    }

    public function edit(BedroomType $bedroomType): View
    {
        return view('admin.bedroom-types.edit', [
            'bedroomType' => $bedroomType,
        ]);
    }

    public function update(Request $request, BedroomType $bedroomType): RedirectResponse
    {
        $data = $this->validateData($request, $bedroomType);

        $bedroomType->update($data);

        return redirect()
            ->route('admin.bedroom-types.index')
            ->with('success', 'Bedroom type updated successfully.');
    }

    public function destroy(BedroomType $bedroomType): RedirectResponse
    {
        $bedroomType->delete();

        return redirect()
            ->route('admin.bedroom-types.index')
            ->with('success', 'Bedroom type deleted successfully.');
    }

    private function validateData(Request $request, ?BedroomType $bedroomType = null): array
    {
        return $request->validate([
            'bedroom_code' => ['required', 'string', 'max:255', Rule::unique('bedroom_types', 'bedroom_code')->ignore($bedroomType?->id)],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
        ]);
    }
}
