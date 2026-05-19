{{-- resources/views/admin/insurance-fees/create.blade.php --}}
@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Create Insurance Fee</h2>
        <a href="{{ route('admin.language-school-insurance-fees.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-2">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.language-school-insurance-fees.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Insurance Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
        </div>

        <div>
            <label for="ar_name" class="block text-sm font-medium text-gray-700">Insurance Name (AR)</label>
            <input type="text" name="ar_name" id="ar_name" value="{{ old('ar_name') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
        </div>

        <div>
            <label for="branch_id" class="block text-sm font-medium text-gray-700">Branch</label>
            <select name="branch_id" id="branch_id"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                <option value="">-- Select Branch --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                        {{ optional($branch->school)->name }} — {{ $branch->slug }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Fee</label>
                <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
            </div>

            <div>
                <label for="admin_charge" class="block text-sm font-medium text-gray-700">Admin Charge</label>
                <input type="number" step="0.01" min="0" name="admin_charge" id="admin_charge" value="{{ old('admin_charge') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="billing_unit" class="block text-sm font-medium text-gray-700">Billing Unit</label>
                <select name="billing_unit" id="billing_unit"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    @foreach(['week','month','course'] as $unit)
                        <option value="{{ $unit }}" @selected(old('billing_unit', 'week') == $unit)>{{ ucfirst($unit) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="billing_count" class="block text-sm font-medium text-gray-700">Billing Count</label>
                <input type="number" min="1" name="billing_count" id="billing_count" value="{{ old('billing_count', 1) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="valid_from" class="block text-sm font-medium text-gray-700">Valid From</label>
                <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
            <div>
                <label for="valid_to" class="block text-sm font-medium text-gray-700">Valid To</label>
                <input type="date" name="valid_to" id="valid_to" value="{{ old('valid_to') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        </div>

        <div class="flex space-x-2 mt-4">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Save</button>
            <a href="{{ route('admin.language-school-insurance-fees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">Cancel</a>
        </div>
    </form>
</div>
@endsection
