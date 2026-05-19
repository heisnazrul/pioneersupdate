<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::orderBy('category')->orderBy('display_order')->paginate(20)->withQueryString();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validateData($request));
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validateData($request));
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'category'      => ['required', 'string', 'max:255'],
            'ar_category'   => ['nullable', 'string', 'max:255'],
            'question'      => ['required', 'string'],
            'ar_question'   => ['nullable', 'string'],
            'answer'        => ['required', 'string'],
            'ar_answer'     => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ]);
        $data['is_active']    = $request->boolean('is_active', true);
        $data['display_order']= (int) ($request->input('display_order', 0) ?? 0);
        return $data;
    }
}
