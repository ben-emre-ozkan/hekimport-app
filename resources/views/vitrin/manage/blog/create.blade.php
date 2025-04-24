@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Create New Blog Post</h1>
                    
                    <a href="{{ route('vitrin.manage.blog.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Back to Posts
                    </a>
                </div>

                <form action="{{ route('vitrin.manage.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt') }}</textarea>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <div class="mt-1">
                            <textarea name="content" id="content" rows="10" required
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Enter tags separated by commas">
                    </div>

                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                        <input type="file" name="featured_image" id="featured_image"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-900">
                            Publish immediately
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 500,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help',
        images_upload_url: '{{ route("vitrin.manage.blog.upload-image") }}',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("vitrin.manage.blog.upload-image") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.url != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.url);
            };
            formData = new FormData();
            formData.append('image', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });
</script>
@endpush
@endsection 