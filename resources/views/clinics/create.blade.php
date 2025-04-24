@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create Clinic</h1>
            <a href="{{ route('clinics.index') }}" class="text-gray-600 hover:text-gray-900">
                Back to Clinics
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('clinics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Clinic Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Address</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Specialties -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Specialties</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($specialties as $specialty)
                            <div class="flex items-center">
                                <input type="checkbox" name="specialties[]" value="{{ $specialty }}" id="specialty_{{ $specialty }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ in_array($specialty, old('specialties', [])) ? 'checked' : '' }}>
                                <label for="specialty_{{ $specialty }}" class="ml-2 block text-sm text-gray-900">
                                    {{ ucfirst($specialty) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('specialties')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Working Hours -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Working Hours</h2>
                    <div class="space-y-4">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <label class="block text-sm font-medium text-gray-700">{{ ucfirst($day) }}</label>
                                </div>
                                <div class="flex-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="time" name="working_hours[{{ $day }}][start]" 
                                            value="{{ old("working_hours.{$day}.start") }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <input type="time" name="working_hours[{{ $day }}][end]"
                                            value="{{ old("working_hours.{$day}.end") }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('working_hours')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Media -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Media</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                class="mt-1 block w-full">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="banner" class="block text-sm font-medium text-gray-700">Banner</label>
                            <input type="file" name="banner" id="banner" accept="image/*"
                                class="mt-1 block w-full">
                            @error('banner')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visibility -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Visibility</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="radio" name="visibility" value="public" id="visibility_public"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                {{ old('visibility', 'public') === 'public' ? 'checked' : '' }}>
                            <label for="visibility_public" class="ml-2 block text-sm text-gray-900">
                                Public
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="visibility" value="private" id="visibility_private"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                {{ old('visibility') === 'private' ? 'checked' : '' }}>
                            <label for="visibility_private" class="ml-2 block text-sm text-gray-900">
                                Private
                            </label>
                        </div>
                    </div>
                    @error('visibility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Create Clinic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 