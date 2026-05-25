<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LanguageSchoolBranchController extends Controller
{
    public function index(): View
    {
        $branches = LanguageSchoolBranch::with(['school', 'city', 'accreditations'])->latest()->paginate(20);

        return view('admin.language-school-branches.index', compact('branches'));
    }

    public function create(): View
    {
        $schools = LanguageSchool::active()->orderBy('name_en')->get();
        $cities = City::active()->with('country')->orderBy('name')->get();
        $accreditations = $this->accreditations();

        return view('admin.language-school-branches.create', compact('schools', 'cities', 'accreditations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'school_id'           => 'required|exists:language_schools,id',
            'city_id'             => 'required|exists:cities,id',
            'slug'                => 'nullable|string|max:255',
            'new_year_close_from' => 'nullable|date',
            'new_year_close_to'   => 'nullable|date',
            'gallery_images'      => 'nullable|array',
            'gallery_images.*'    => 'nullable|string|max:255',
            'accreditation_ids'   => 'nullable|array',
            'accreditation_ids.*' => 'integer|exists:accreditations,id',
            'is_active'           => 'required|in:yes,no',
        ]);

        if (!$request->filled('slug')) {
            $school = LanguageSchool::findOrFail($data['school_id']);
            $city = City::findOrFail($data['city_id']);
            $data['slug'] = Str::slug($school->name_en . '-' . $city->name);
        }

        $data['branch_images'] = $request->gallery_images ?? [];

        DB::transaction(function () use ($data, $request): void {
            $branch = LanguageSchoolBranch::create($data);
            $branch->accreditations()->sync($request->input('accreditation_ids', []));
        });

        return redirect()->route('admin.language-school-branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(LanguageSchoolBranch $languageSchoolBranch): View
    {
        $languageSchoolBranch->load('accreditations');
        $schools = LanguageSchool::orderBy('name_en')->get();
        $cities = City::with('country')->orderBy('name')->get();
        $accreditations = $this->accreditations();

        return view('admin.language-school-branches.edit', compact('languageSchoolBranch', 'schools', 'cities', 'accreditations'));
    }

    public function update(Request $request, LanguageSchoolBranch $languageSchoolBranch): RedirectResponse
    {
        $data = $request->validate([
            'school_id'           => 'required|exists:language_schools,id',
            'city_id'             => 'required|exists:cities,id',
            'slug'                => 'required|string|max:255',
            'new_year_close_from' => 'nullable|date',
            'new_year_close_to'   => 'nullable|date',
            'gallery_images'      => 'nullable|array',
            'gallery_images.*'    => 'nullable|string|max:255',
            'accreditation_ids'   => 'nullable|array',
            'accreditation_ids.*' => 'integer|exists:accreditations,id',
            'is_active'           => 'required|in:yes,no',
        ]);

        $data['branch_images'] = $request->gallery_images ?? [];

        DB::transaction(function () use ($data, $languageSchoolBranch, $request): void {
            $languageSchoolBranch->update($data);
            $languageSchoolBranch->accreditations()->sync($request->input('accreditation_ids', []));
        });

        return redirect()->route('admin.language-school-branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(LanguageSchoolBranch $languageSchoolBranch): RedirectResponse
    {
        if ($languageSchoolBranch->branch_images) {
            foreach ($languageSchoolBranch->branch_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $languageSchoolBranch->delete();

        return redirect()->route('admin.language-school-branches.index')
            ->with('success', 'Branch deleted successfully.');
    }

    private function accreditations(): Collection
    {
        return Accreditation::query()
            ->orderBy('name')
            ->get();
    }
}
