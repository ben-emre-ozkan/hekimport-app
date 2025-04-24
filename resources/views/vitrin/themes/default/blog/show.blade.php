@extends('vitrin.themes.default.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <article class="prose max-w-none">
                    @if($blog->featured_image)
                    <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="w-full h-64 object-cover rounded-lg mb-8">
                    @endif
                    
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $blog->title }}</h1>
                    
                    <div class="flex items-center text-gray-500 mb-8">
                        <span>{{ $blog->published_at->format('F j, Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $blog->author->name }}</span>
                        @if($blog->category)
                        <span class="mx-2">•</span>
                        <span>{{ $blog->category->name }}</span>
                        @endif
                    </div>
                    
                    <div class="prose prose-lg">
                        {!! $blog->content !!}
                    </div>
                    
                    @if($blog->tags->count() > 0)
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Tags</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($blog->tags as $tag)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($blog->related_posts->count() > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Related Posts</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($blog->related_posts as $related)
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('vitrin.blog.show', ['subdomain' => $vitrin->subdomain, 'slug' => $related->slug]) }}" 
                                       class="hover:text-indigo-600">
                                        {{ $related->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($related->excerpt, 100) }}</p>
                                <a href="{{ route('vitrin.blog.show', ['subdomain' => $vitrin->subdomain, 'slug' => $related->slug]) }}" 
                                   class="text-indigo-600 hover:text-indigo-800">
                                    Read More →
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </article>
            </div>
        </div>
    </div>
</div>
@endsection 