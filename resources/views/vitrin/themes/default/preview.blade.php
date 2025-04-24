@extends('vitrin.themes.default.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Preview Mode</h1>
                    
                    <div class="flex items-center space-x-4">
                        <select id="theme-selector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="default">Default Theme</option>
                            <option value="modern">Modern Theme</option>
                            <option value="minimal">Minimal Theme</option>
                        </select>
                        
                        <a href="{{ route('vitrin.edit', $vitrin) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Edit Vitrin
                        </a>
                    </div>
                </div>
                
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <p class="text-gray-600">
                        You are viewing a preview of your vitrin page. Changes made here will not be visible to the public until you publish them.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Links</h2>
                        <div class="space-y-2">
                            <a href="{{ route('vitrin.home', ['subdomain' => $vitrin->subdomain]) }}" 
                               class="block px-4 py-2 bg-gray-50 rounded-md text-gray-700 hover:bg-gray-100">
                                Home Page
                            </a>
                            <a href="{{ route('vitrin.about', ['subdomain' => $vitrin->subdomain]) }}" 
                               class="block px-4 py-2 bg-gray-50 rounded-md text-gray-700 hover:bg-gray-100">
                                About Page
                            </a>
                            <a href="{{ route('vitrin.blog', ['subdomain' => $vitrin->subdomain]) }}" 
                               class="block px-4 py-2 bg-gray-50 rounded-md text-gray-700 hover:bg-gray-100">
                                Blog Page
                            </a>
                            <a href="{{ route('vitrin.contact', ['subdomain' => $vitrin->subdomain]) }}" 
                               class="block px-4 py-2 bg-gray-50 rounded-md text-gray-700 hover:bg-gray-100">
                                Contact Page
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Preview Settings</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Device Preview</label>
                                <div class="mt-2 flex space-x-4">
                                    <button type="button" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200">
                                        Desktop
                                    </button>
                                    <button type="button" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200">
                                        Tablet
                                    </button>
                                    <button type="button" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200">
                                        Mobile
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Preview Mode</label>
                                <div class="mt-2 flex space-x-4">
                                    <button type="button" class="px-4 py-2 bg-indigo-100 rounded-md text-indigo-700 hover:bg-indigo-200">
                                        Live Preview
                                    </button>
                                    <button type="button" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200">
                                        Draft Preview
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('theme-selector').addEventListener('change', function(e) {
        // Handle theme switching logic here
        console.log('Theme changed to:', e.target.value);
    });
</script>
@endpush
@endsection 