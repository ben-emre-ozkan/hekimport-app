@extends('vitrin.themes.default.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">About Us</h1>
                
                <div class="prose max-w-none">
                    <p class="text-lg text-gray-600 mb-6">
                        {{ $vitrin->description }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Mission</h2>
                            <p class="text-gray-600">
                                {{ $vitrin->mission }}
                            </p>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Vision</h2>
                            <p class="text-gray-600">
                                {{ $vitrin->vision }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Team</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($vitrin->team_members as $member)
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $member->name }}</h3>
                                <p class="text-gray-600 mb-2">{{ $member->position }}</p>
                                <p class="text-gray-600">{{ $member->bio }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Values</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($vitrin->values as $value)
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $value->title }}</h3>
                                <p class="text-gray-600">{{ $value->description }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 