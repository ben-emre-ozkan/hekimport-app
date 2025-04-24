@extends('vitrin.themes.default.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Blog</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($blogs as $blog)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        @if($blog->featured_image)
                        <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                        @endif
                        
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">
                            <a href="{{ route('vitrin.blog.show', ['subdomain' => $vitrin->subdomain, 'slug' => $blog->slug]) }}" 
                               class="hover:text-indigo-600">
                                {{ $blog->title }}
                            </a>
                        </h2>
                        
                        <div class="text-sm text-gray-500 mb-4">
                            {{ $blog->published_at->format('F j, Y') }} • {{ $blog->author->name }}
                        </div>
                        
                        <p class="text-gray-600 mb-4">{{ Str::limit($blog->excerpt, 150) }}</p>
                        
                        <a href="{{ route('vitrin.blog.show', ['subdomain' => $vitrin->subdomain, 'slug' => $blog->slug]) }}" 
                           class="text-indigo-600 hover:text-indigo-800">
                            Read More →
                        </a>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $blogs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 