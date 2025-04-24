@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Media Library</h1>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('vitrin.manage.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Back to Dashboard
                        </a>
                        
                        <button type="button" onclick="document.getElementById('media-upload').click()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Upload Media
                        </button>
                        <input type="file" id="media-upload" multiple class="hidden" accept="image/*,video/*">
                    </div>
                </div>

                <!-- Upload Progress -->
                <div id="upload-progress" class="hidden mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Uploading files...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($media as $item)
                    <div class="relative group">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                            @if(Str::startsWith($item->type, 'image/'))
                            <img src="{{ Storage::url($item->path) }}" alt="{{ $item->name }}" 
                                 class="w-full h-full object-cover">
                            @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="flex space-x-2">
                                    <button type="button" onclick="copyUrl('{{ Storage::url($item->path) }}')"
                                            class="p-2 bg-white rounded-full text-gray-700 hover:text-indigo-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                    
                                    <form action="{{ route('vitrin.manage.media.delete', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this file?')"
                                                class="p-2 bg-white rounded-full text-gray-700 hover:text-red-600">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $media->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('media-upload').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length === 0) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('media[]', files[i]);
        }

        const progress = document.getElementById('upload-progress');
        progress.classList.remove('hidden');

        fetch('{{ route("vitrin.manage.media.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            progress.classList.add('hidden');
            if (data.message) {
                window.location.reload();
            }
        })
        .catch(error => {
            progress.classList.add('hidden');
            console.error('Error:', error);
        });
    });

    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('URL copied to clipboard!');
        });
    }
</script>
@endpush
@endsection 