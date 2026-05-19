@extends('layouts.admin')

@section('title', 'Gallery Management')
@section('header', 'Gallery')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2 rounded-lg {{ !request('use_case') ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
            All
        </a>
        @foreach($useCases as $useCase)
            <a href="{{ route('admin.galleries.index', ['use_case' => $useCase]) }}" class="px-4 py-2 rounded-lg {{ request('use_case') == $useCase ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
                {{ ucfirst($useCase) }}
            </a>
        @endforeach
    </div>
    <a href="{{ route('admin.galleries.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Media
    </a>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    @forelse($galleries as $gallery)
    <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden aspect-square">
        <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->alt_text }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center p-4 text-center">
            <p class="text-white text-xs font-semibold mb-1">{{ $gallery->title ?? 'Untitled' }}</p>
            <span class="text-white/70 text-[10px] uppercase tracking-wider mb-3">{{ $gallery->use_case ?? 'No use case' }}</span>
            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center text-gray-500">
        <i class="fa-solid fa-images text-4xl mb-3 block opacity-20"></i>
        No media found in the gallery.
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $galleries->links() }}
</div>
@endsection
