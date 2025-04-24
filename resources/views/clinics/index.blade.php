@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Clinics</h1>
        @can('create', App\Models\Clinic::class)
            <a href="{{ route('clinics.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Create Clinic
            </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('clinics.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search clinics...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Specialty</label>
                <select name="specialty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Specialties</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                            {{ ucfirst($specialty) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Visibility</label>
                <select name="visibility" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Visibility</option>
                    <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Clinics List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($clinics as $clinic)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($clinic->banner)
                    <img src="{{ Storage::url($clinic->banner) }}" alt="{{ $clinic->name }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                    <div class="flex items-center mb-4">
                        @if($clinic->logo)
                            <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $clinic->name }}" class="w-12 h-12 rounded-full mr-4">
                        @endif
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $clinic->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $clinic->city }}, {{ $clinic->country }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">{{ Str::limit($clinic->description, 100) }}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($clinic->specialties as $specialty)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                {{ ucfirst($specialty) }}
                            </span>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($clinic->status === 'active') bg-green-100 text-green-800
                                @elseif($clinic->status === 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($clinic->status) }}
                            </span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                @if($clinic->visibility === 'public') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($clinic->visibility) }}
                            </span>
                        </div>
                        <a href="{{ route('clinics.show', $clinic) }}" class="text-blue-600 hover:text-blue-900">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $clinics->links() }}
    </div>
</div>
@endsection 