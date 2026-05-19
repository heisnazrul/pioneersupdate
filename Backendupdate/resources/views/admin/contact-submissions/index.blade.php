@extends('layouts.admin')

@section('title', 'Inquiries Management')
@section('header', 'Inquiries')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('admin.contact-submissions.index') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
            All
        </a>
        @foreach($statuses as $status)
            <a href="{{ route('admin.contact-submissions.index', ['status' => $status]) }}" class="px-4 py-2 rounded-lg {{ request('status') == $status ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
                {{ ucfirst($status) }}
            </a>
        @endforeach
    </div>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Subject</th>
                <th class="px-6 py-4 font-semibold">Sender</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold">Date</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($submissions as $sub)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sub->subject }}</p>
                    <p class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($sub->message, 50) }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->name }}</p>
                    <p class="text-xs text-gray-500">{{ $sub->email }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sub->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($sub->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-xs text-gray-500">
                    {{ $sub->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.contact-submissions.show', $sub) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.contact-submissions.destroy', $sub) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-500">No inquiries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
