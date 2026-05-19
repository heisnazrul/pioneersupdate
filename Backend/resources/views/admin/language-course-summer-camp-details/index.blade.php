@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Summer Camp Details</h2>
        <a href="{{ route('admin.language-course-summer-camp-details.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Create</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Camp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Images</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($details as $detail)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $detail->camp->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $detail->camp->branch->school->name ?? '—' }} / {{ $detail->camp->branch->slug ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ is_array($detail->images) ? count($detail->images) : 0 }}
                        </td>
                        <td class="px-6 py-4 text-sm">{{ optional($detail->updated_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.language-course-summer-camp-details.edit', $detail) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.language-course-summer-camp-details.destroy', $detail) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-danger hover:underline" onclick="return confirm('Delete this detail record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">No summer camp details.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $details->links() }}</div>
</div>
@endsection
