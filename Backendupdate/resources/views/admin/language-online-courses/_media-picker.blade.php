@include('admin.partials.media-picker-modal', ['title' => 'Select Online Course Thumbnail'])

<script>
function mediaPicker() {
    return {
        showModal: false,
        tab: 'gallery',
        loading: false,
        uploading: false,
        searchTerm: '',
        images: [],
        selectedId: null,
        tempSelected: null,
        selectedUrl: '{{ old("gallery_thumbnail", $course->thumbnail ?? null) ? Storage::url(old("gallery_thumbnail", $course->thumbnail ?? null)) : "" }}',
        selectedPath: '{{ old("gallery_thumbnail", $course->thumbnail ?? "") }}',
        selectedTitle: '',
        uploadFile: null,
        uploadPreview: null,
        uploadTitle: '',

        openModal() {
            this.showModal = true;
            this.tab = 'gallery';
            this.tempSelected = this.selectedPath
                ? { id: this.selectedId, path: this.selectedPath, url: this.selectedUrl, title: this.selectedTitle }
                : null;
            this.selectedId = this.tempSelected?.id ?? null;
            this.fetchImages();
        },

        async fetchImages() {
            this.loading = true;
            try {
                const response = await fetch(`{{ route('admin.galleries.search') }}?use_case=online_course&search=${this.searchTerm}`);
                this.images = await response.json();
            } catch (e) {
                console.error('Failed to fetch images', e);
            }
            this.loading = false;
        },

        selectImage(img) {
            this.selectedId = img.id;
            this.tempSelected = img;
        },

        handleFileUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.uploadFile = file;
            this.uploadPreview = URL.createObjectURL(file);
            if (!this.uploadTitle) this.uploadTitle = file.name.split('.')[0];
        },

        async uploadAndSelect() {
            if (!this.uploadFile) return;

            this.uploading = true;
            const formData = new FormData();
            formData.append('image', this.uploadFile);
            formData.append('title', this.uploadTitle);
            formData.append('use_case', 'online_course');
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch(`{{ route('admin.galleries.api-store') }}`, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error('Upload failed');

                const result = await response.json();
                this.tempSelected = result;
                this.selectedId = result.id;
                this.images = [result, ...this.images.filter((img) => img.id !== result.id)];
                this.tab = 'gallery';
                this.uploadFile = null;
                this.uploadPreview = null;
                this.uploadTitle = '';
            } catch (e) {
                alert('Upload failed. Please try again.');
            }

            this.uploading = false;
        },

        confirmSelection() {
            if (!this.tempSelected) return;

            this.selectedUrl = this.tempSelected.url;
            this.selectedPath = this.tempSelected.path;
            this.selectedTitle = this.tempSelected.title ?? '';
            this.selectedId = this.tempSelected.id ?? null;
            this.showModal = false;
        }
    }
}
</script>
