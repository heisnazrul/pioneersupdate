<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccreditationController extends Controller
{
    public function index(): View
    {
        $accreditations = Accreditation::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.accreditations.index', [
            'accreditations' => $accreditations,
        ]);
    }

    public function create(): View
    {
        return view('admin.accreditations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('accreditations', 'public');
        }

        Accreditation::create($data);

        return redirect()
            ->route('admin.accreditations.index')
            ->with('success', 'Accreditation created successfully.');
    }

    public function edit(Accreditation $accreditation): View
    {
        return view('admin.accreditations.edit', [
            'accreditation' => $accreditation,
        ]);
    }

    public function update(Request $request, Accreditation $accreditation): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->boolean('remove_picture')) {
            $this->deletePicture($accreditation->picture);
            $data['picture'] = null;
        }

        if ($request->hasFile('picture')) {
            $this->deletePicture($accreditation->picture);
            $data['picture'] = $request->file('picture')->store('accreditations', 'public');
        }

        unset($data['remove_picture']);

        $accreditation->update($data);

        return redirect()
            ->route('admin.accreditations.index')
            ->with('success', 'Accreditation updated successfully.');
    }

    public function destroy(Accreditation $accreditation): RedirectResponse
    {
        $this->deletePicture($accreditation->picture);
        $accreditation->delete();

        return redirect()
            ->route('admin.accreditations.index')
            ->with('success', 'Accreditation deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'picture' => ['nullable', 'image', 'max:2048'],
            'remove_picture' => ['sometimes', 'boolean'],
        ]);
    }

    private function deletePicture(?string $path): void
    {
        if ($path && str_starts_with($path, 'accreditations/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
