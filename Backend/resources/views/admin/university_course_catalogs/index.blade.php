@extends('admin.layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold">University Course Catalog</h2>
            <p class="text-sm text-gray-500">Manage global course catalog and available levels.</p>
        </div>
        <a href="{{ route('admin.university-course-catalogs.create') }}" class="ti-btn ti-btn-primary !m-0">Add Course</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject Area</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Levels</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($catalogs as $catalog)
                    <tr>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $catalog->name }}</div>
                            <div class="text-xs text-gray-500">{{ $catalog->slug }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $catalog->subjectArea->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-wrap gap-1">
                                @foreach($catalog->levels as $level)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700">{{ $level->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs {{ $catalog->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $catalog->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.university-course-catalogs.edit', $catalog) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.university-course-catalogs.destroy', $catalog) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this course catalog?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">No course catalog entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $catalogs->links() }}
    </div>
</div>
@endsection
