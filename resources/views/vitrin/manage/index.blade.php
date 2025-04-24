@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Vitrin Management</h1>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('vitrin.manage.preview') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Preview
                        </a>
                        
                        @if($vitrin->is_published)
                        <form action="{{ route('vitrin.manage.unpublish') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Unpublish
                            </button>
                        </form>
                        @else
                        <form action="{{ route('vitrin.manage.publish') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Publish
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Info Card -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <p class="mt-1 text-gray-900">{{ $vitrin->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Subdomain</label>
                                <p class="mt-1 text-gray-900">{{ $vitrin->subdomain }}.hekimport.com</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Theme</label>
                                <p class="mt-1 text-gray-900">{{ ucfirst($vitrin->theme) }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('vitrin.manage.edit') }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                Edit Basic Info →
                            </a>
                        </div>
                    </div>

                    <!-- Content Management Card -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Content Management</h2>
                        <div class="space-y-4">
                            <div>
                                <a href="{{ route('vitrin.manage.blog.index') }}" 
                                   class="block text-gray-900 hover:text-indigo-600">
                                    Blog Posts ({{ $vitrin->blogs()->count() }})
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('vitrin.manage.media') }}" 
                                   class="block text-gray-900 hover:text-indigo-600">
                                    Media Library ({{ $vitrin->media()->count() }})
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistics</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Visitors</label>
                                <p class="mt-1 text-gray-900">{{ $vitrin->visitors()->count() }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Page Views</label>
                                <p class="mt-1 text-gray-900">{{ $vitrin->page_views()->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('vitrin.manage.statistics') }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                View Detailed Statistics →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('vitrin.manage.blog.create') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            New Blog Post
                        </a>
                        <a href="{{ route('vitrin.manage.media') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Upload Media
                        </a>
                        <a href="{{ route('vitrin.manage.edit') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Edit Settings
                        </a>
                        <a href="{{ route('vitrin.manage.preview') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Preview Changes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 