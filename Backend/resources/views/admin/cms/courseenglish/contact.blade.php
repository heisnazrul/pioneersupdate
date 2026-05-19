@extends('admin.layouts.layout')

@php
    $content = $content ?? [];
    $arContent = $arContent ?? [];
    $cards = $content['cards'] ?? [];
    $cardsAr = $arContent['cards'] ?? [];
    $cardCount = max(count($cards), count($cardsAr), 3);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Contact Us Page</h2>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.cms.course-english.contact.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Breadcrumb + Title</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Home (EN)</label>
                        <input name="breadcrumb[home]" value="{{ old('breadcrumb.home', $content['breadcrumb']['home'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Home (AR)</label>
                        <input name="breadcrumb[home_ar]" value="{{ old('breadcrumb.home_ar', $arContent['breadcrumb']['home'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current (EN)</label>
                        <input name="breadcrumb[current]" value="{{ old('breadcrumb.current', $content['breadcrumb']['current'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current (AR)</label>
                        <input name="breadcrumb[current_ar]" value="{{ old('breadcrumb.current_ar', $arContent['breadcrumb']['current'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Page Title (EN)</label>
                        <input name="title" value="{{ old('title', $content['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Page Title (AR)</label>
                        <input name="title_ar" value="{{ old('title_ar', $arContent['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Contact Cards</h3>
                <p class="text-xs text-gray-500 mb-3">Use icon values: <code>phone</code>, <code>whatsapp</code>, <code>email</code>.</p>
                <div class="space-y-4">
                    @for($i = 0; $i < $cardCount; $i++)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border rounded p-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card {{ $i + 1 }} Icon</label>
                                <input name="cards[{{ $i }}][icon]" value="{{ old("cards.$i.icon", $cards[$i]['icon'] ?? $cardsAr[$i]['icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card {{ $i + 1 }} Link</label>
                                <input name="cards[{{ $i }}][href]" value="{{ old("cards.$i.href", $cards[$i]['href'] ?? $cardsAr[$i]['href'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card {{ $i + 1 }} Label (EN)</label>
                                <input name="cards[{{ $i }}][label]" value="{{ old("cards.$i.label", $cards[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card {{ $i + 1 }} Label (AR)</label>
                                <input name="cards[{{ $i }}][label_ar]" value="{{ old("cards.$i.label_ar", $cardsAr[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card {{ $i + 1 }} Value (EN)</label>
                                <input name="cards[{{ $i }}][value]" value="{{ old("cards.$i.value", $cards[$i]['value'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card {{ $i + 1 }} Value (AR)</label>
                                <input name="cards[{{ $i }}][value_ar]" value="{{ old("cards.$i.value_ar", $cardsAr[$i]['value'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Form Text</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Form Title (EN)</label>
                        <input name="form[title]" value="{{ old('form.title', $content['form']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Form Title (AR)</label>
                        <input name="form[title_ar]" value="{{ old('form.title_ar', $arContent['form']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name Label (EN)</label>
                        <input name="form[full_name_label]" value="{{ old('form.full_name_label', $content['form']['full_name_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name Label (AR)</label>
                        <input name="form[full_name_label_ar]" value="{{ old('form.full_name_label_ar', $arContent['form']['full_name_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name Placeholder (EN)</label>
                        <input name="form[full_name_placeholder]" value="{{ old('form.full_name_placeholder', $content['form']['full_name_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name Placeholder (AR)</label>
                        <input name="form[full_name_placeholder_ar]" value="{{ old('form.full_name_placeholder_ar', $arContent['form']['full_name_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Label (EN)</label>
                        <input name="form[email_label]" value="{{ old('form.email_label', $content['form']['email_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Label (AR)</label>
                        <input name="form[email_label_ar]" value="{{ old('form.email_label_ar', $arContent['form']['email_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Placeholder (EN)</label>
                        <input name="form[email_placeholder]" value="{{ old('form.email_placeholder', $content['form']['email_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Placeholder (AR)</label>
                        <input name="form[email_placeholder_ar]" value="{{ old('form.email_placeholder_ar', $arContent['form']['email_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message Label (EN)</label>
                        <input name="form[message_label]" value="{{ old('form.message_label', $content['form']['message_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message Label (AR)</label>
                        <input name="form[message_label_ar]" value="{{ old('form.message_label_ar', $arContent['form']['message_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message Placeholder (EN)</label>
                        <input name="form[message_placeholder]" value="{{ old('form.message_placeholder', $content['form']['message_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message Placeholder (AR)</label>
                        <input name="form[message_placeholder_ar]" value="{{ old('form.message_placeholder_ar', $arContent['form']['message_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Submit Text (EN)</label>
                        <input name="form[submit_text]" value="{{ old('form.submit_text', $content['form']['submit_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Submit Text (AR)</label>
                        <input name="form[submit_text_ar]" value="{{ old('form.submit_text_ar', $arContent['form']['submit_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sending Text (EN)</label>
                        <input name="form[sending_text]" value="{{ old('form.sending_text', $content['form']['sending_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sending Text (AR)</label>
                        <input name="form[sending_text_ar]" value="{{ old('form.sending_text_ar', $arContent['form']['sending_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Success Text (EN)</label>
                        <input name="form[success_text]" value="{{ old('form.success_text', $content['form']['success_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Success Text (AR)</label>
                        <input name="form[success_text_ar]" value="{{ old('form.success_text_ar', $arContent['form']['success_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fail Text (EN)</label>
                        <input name="form[fail_text]" value="{{ old('form.fail_text', $content['form']['fail_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fail Text (AR)</label>
                        <input name="form[fail_text_ar]" value="{{ old('form.fail_text_ar', $arContent['form']['fail_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Contact Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
