@extends('vitrin.themes.default.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $vitrin->name }}</h1>
                    <p class="text-xl text-gray-600 mb-8">{{ $vitrin->description }}</p>
                    <a href="{{ route('vitrin.contact', ['subdomain' => $vitrin->subdomain]) }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($vitrin->services as $service)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-600">{{ $service->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Blog Posts -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Blog Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($recentBlogs as $blog)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('vitrin.blog.show', ['subdomain' => $vitrin->subdomain, 'slug' => $blog->slug]) }}" 
                               class="hover:text-indigo-600">
                                {{ $blog->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($blog->excerpt, 100) }}</p>
                        <a href="{{ route('vitrin.blog.show', ['subdomain' => $vitrin->subdomain, 'slug' => $blog->slug]) }}" 
                           class="text-indigo-600 hover:text-indigo-800">
                            Read More →
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('vitrin.blog', ['subdomain' => $vitrin->subdomain]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50">
                        View All Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 