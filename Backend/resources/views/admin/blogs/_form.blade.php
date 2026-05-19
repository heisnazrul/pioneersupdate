@php
    $isEdit = isset($blog) && $blog?->exists;
    $defaults = [
        'title' => old('title', $blog->title ?? ''),
        'ar_title' => old('ar_title', $blog->ar_title ?? ''),
        'slug' => old('slug', $blog->slug ?? ''),
        'category_id' => old('category_id', $blog->category_id ?? ''),
        'audience_scope' => old('audience_scope', $blog->audience_scope ?? 'all'),
        'summary' => old('summary', $blog->summary ?? ''),
        'ar_summary' => old('ar_summary', $blog->ar_summary ?? ''),
        'content' => old('content', $blog->content ?? ''),
        'ar_content' => old('ar_content', $blog->ar_content ?? ''),
        'published_at' => old('published_at', optional($blog->published_at ?? null)->format('Y-m-d\TH:i')),
    ];

    $featuredImage = $blog->featured_image ?? null;
    $featuredImageUrl = $featuredImage ? asset('storage/' . ltrim($featuredImage, '/')) : null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" id="title" name="title" value="{{ $defaults['title'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
        <input type="text" name="ar_title" value="{{ $defaults['ar_title'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ $defaults['slug'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Category</label>
        <select name="category_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected($defaults['category_id'] == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tags</label>
        <select name="tags[]" class="mt-1 block w-full border rounded-md px-3 py-2" multiple>
            @foreach($blogTags as $tag)
                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $blog?->tags->pluck('id')->toArray() ?? [])))>{{ $tag->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple tags</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Audience</label>
        <select name="audience_scope" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            @foreach($audienceScopes as $scope)
                <option value="{{ $scope }}" @selected($defaults['audience_scope'] === $scope)>{{ ucfirst($scope) }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Controls who sees this blog post.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Featured Image</label>
        <input type="file" name="featured_image" accept="image/*" class="mt-1 block w-full border rounded-md px-3 py-2">
        @if($featuredImageUrl)
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ $featuredImageUrl }}" alt="Featured" class="w-20 h-20 rounded object-cover border">
                <label class="inline-flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remove_featured_image" value="1"
                        class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="ml-2">Remove current image</span>
                </label>
            </div>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Publish At (optional)</label>
        <input type="datetime-local" name="published_at" value="{{ $defaults['published_at'] }}"
            class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Summary</label>
        <textarea name="summary" rows="3"
            class="mt-1 block w-full border rounded-md px-3 py-2">{{ $defaults['summary'] }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Summary (AR)</label>
        <textarea name="ar_summary" rows="3"
            class="mt-1 block w-full border rounded-md px-3 py-2">{{ $defaults['ar_summary'] }}</textarea>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Content</label>
        <input id="content" type="hidden" name="content" value="{{ $defaults['content'] }}">
        <trix-editor input="content" class="trix-content border rounded-md"></trix-editor>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Content (AR)</label>
        <input id="ar_content" type="hidden" name="ar_content" value="{{ $defaults['ar_content'] }}">
        <trix-editor input="ar_content" class="trix-content border rounded-md"></trix-editor>
    </div>
</div>

<div class="flex space-x-2">
    <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
        {{ $isEdit ? 'Update Blog' : 'Save Blog' }}
    </button>
    <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 border rounded-full">Cancel</a>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        (() => {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            if (!titleInput || !slugInput) {
                return;
            }

            const slugify = (text) => text.toString().toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)+/g, '')
                .substring(0, 255);

            let touched = slugInput.value.trim().length > 0;

            slugInput.addEventListener('input', () => {
                touched = slugInput.value.trim().length > 0;
            });

            titleInput.addEventListener('input', () => {
                if (!touched) {
                    slugInput.value = slugify(titleInput.value);
                }
            });
        })();

        document.addEventListener('trix-file-accept', function (event) {
            event.preventDefault();
        });
    </script>
@endpush