@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Vitrin Settings</h1>
                    
                    <a href="{{ route('vitrin.manage.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Back to Dashboard
                    </a>
                </div>

                <form action="{{ route('vitrin.manage.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $vitrin->name) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $vitrin->subdomain) }}" required
                                           class="block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        .hekimport.com
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $vitrin->description) }}</textarea>
                    </div>

                    <!-- Mission & Vision -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mission" class="block text-sm font-medium text-gray-700">Mission</label>
                            <textarea name="mission" id="mission" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('mission', $vitrin->mission) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="vision" class="block text-sm font-medium text-gray-700">Vision</label>
                            <textarea name="vision" id="vision" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('vision', $vitrin->vision) }}</textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" id="address" rows="2" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $vitrin->address) }}</textarea>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $vitrin->phone) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $vitrin->email) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="business_hours" class="block text-sm font-medium text-gray-700">Business Hours</label>
                                <input type="text" name="business_hours" id="business_hours" value="{{ old('business_hours', $vitrin->business_hours) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Theme & SEO -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Theme & SEO</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="theme" class="block text-sm font-medium text-gray-700">Theme</label>
                                <select name="theme" id="theme" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="default" {{ old('theme', $vitrin->theme) === 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="modern" {{ old('theme', $vitrin->theme) === 'modern' ? 'selected' : '' }}>Modern</option>
                                    <option value="minimal" {{ old('theme', $vitrin->theme) === 'minimal' ? 'selected' : '' }}>Minimal</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="seo_title" class="block text-sm font-medium text-gray-700">SEO Title</label>
                                <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $vitrin->seo_title) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="seo_description" class="block text-sm font-medium text-gray-700">SEO Description</label>
                                <textarea name="seo_description" id="seo_description" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('seo_description', $vitrin->seo_description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Social Media</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                                <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $vitrin->facebook) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="twitter" class="block text-sm font-medium text-gray-700">Twitter</label>
                                <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $vitrin->twitter) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                                <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $vitrin->instagram) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn</label>
                                <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $vitrin->linkedin) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Media</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                @if($vitrin->logo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($vitrin->logo) }}" alt="Current Logo" class="h-20 w-auto">
                                </div>
                                @endif
                                <input type="file" name="logo" id="logo"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            
                            <div>
                                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
                                @if($vitrin->cover_image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($vitrin->cover_image) }}" alt="Current Cover Image" class="h-20 w-auto">
                                </div>
                                @endif
                                <input type="file" name="cover_image" id="cover_image"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 