@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            @if($clinic->banner)
                <img src="{{ Storage::url($clinic->banner) }}" alt="{{ $clinic->name }}" class="w-full h-64 object-cover">
            @endif
            <div class="p-6">
                <div class="flex items-center mb-4">
                    @if($clinic->logo)
                        <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $clinic->name }}" class="w-16 h-16 rounded-full mr-4">
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $clinic->name }}</h1>
                        <p class="text-gray-600">{{ $clinic->city }}, {{ $clinic->country }}</p>
                    </div>
                    <div class="ml-auto flex items-center space-x-4">
                        @if($clinic->hasMember(auth()->user()))
                            <form action="{{ route('clinics.disconnect', $clinic) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to disconnect from this clinic?')">
                                    Disconnect
                                </button>
                            </form>
                        @else
                            <form action="{{ route('clinics.connect', $clinic) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Connect
                                </button>
                            </form>
                        @endif
                        @can('update', $clinic)
                            <a href="{{ route('clinics.edit', $clinic) }}" class="text-gray-600 hover:text-gray-900">
                                Edit
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($clinic->specialties as $specialty)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            {{ ucfirst($specialty) }}
                        </span>
                    @endforeach
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($clinic->status === 'active') bg-green-100 text-green-800
                        @elseif($clinic->status === 'inactive') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($clinic->status) }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($clinic->visibility === 'public') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($clinic->visibility) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="#overview" class="tab-link active py-4 px-6 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                        Overview
                    </a>
                    <a href="#members" class="tab-link py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Members
                    </a>
                    <a href="#resources" class="tab-link py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Resources
                    </a>
                    @can('update', $clinic)
                        <a href="#settings" class="tab-link py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Settings
                        </a>
                    @endcan
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Overview -->
            <div id="overview" class="tab-pane active">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About</h2>
                    <p class="text-gray-600 mb-6">{{ $clinic->description }}</p>

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-gray-900">{{ $clinic->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-gray-900">{{ $clinic->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Website</p>
                            <p class="text-gray-900">
                                <a href="{{ $clinic->website }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                    {{ $clinic->website }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="text-gray-900">
                                {{ $clinic->address }}<br>
                                {{ $clinic->city }}, {{ $clinic->state }} {{ $clinic->postal_code }}<br>
                                {{ $clinic->country }}
                            </p>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Working Hours</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($clinic->working_hours as $day => $hours)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ ucfirst($day) }}</span>
                                <span class="text-gray-900">
                                    {{ $hours['start'] }} - {{ $hours['end'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Members -->
            <div id="members" class="tab-pane hidden">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Members</h2>
                        @can('manage', $clinic)
                            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" onclick="showInviteModal()">
                                Invite Member
                            </button>
                        @endcan
                    </div>

                    <!-- Doctors -->
                    <div class="mb-8">
                        <h3 class="text-md font-semibold text-gray-900 mb-4">Doctors</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($doctors as $doctor)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <img src="{{ $doctor->profile->avatar_url }}" alt="{{ $doctor->name }}" class="w-12 h-12 rounded-full mr-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $doctor->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $doctor->profile->specialty }}</p>
                                        </div>
                                        @can('manage', $clinic)
                                            <div class="ml-auto">
                                                <button type="button" class="text-gray-600 hover:text-gray-900" onclick="showRoleModal({{ $doctor->id }})">
                                                    Change Role
                                                </button>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Staff -->
                    <div>
                        <h3 class="text-md font-semibold text-gray-900 mb-4">Staff</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($staff as $member)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <img src="{{ $member->profile->avatar_url }}" alt="{{ $member->name }}" class="w-12 h-12 rounded-full mr-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $member->pivot->role }}</p>
                                        </div>
                                        @can('manage', $clinic)
                                            <div class="ml-auto">
                                                <button type="button" class="text-gray-600 hover:text-gray-900" onclick="showRoleModal({{ $member->id }})">
                                                    Change Role
                                                </button>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resources -->
            <div id="resources" class="tab-pane hidden">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Shared Resources</h2>
                        @can('shareResources', $clinic)
                            <a href="{{ route('clinics.shared-resources.create', $clinic) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Share Resource
                            </a>
                        @endcan
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sharedResources as $resource)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($resource->type === 'file') bg-blue-100 text-blue-800
                                        @elseif($resource->type === 'document') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($resource->type) }}
                                    </span>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                        @if($resource->status === 'active') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($resource->status) }}
                                    </span>
                                </div>
                                <h3 class="font-medium text-gray-900 mb-2">{{ $resource->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($resource->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">
                                        Shared by {{ $resource->sharer->name }}
                                    </span>
                                    <a href="{{ route('clinics.shared-resources.show', [$clinic, $resource]) }}" class="text-blue-600 hover:text-blue-900">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $sharedResources->links() }}
                    </div>
                </div>
            </div>

            <!-- Settings -->
            @can('update', $clinic)
                <div id="settings" class="tab-pane hidden">
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Clinic Settings</h2>
                        <form action="{{ route('clinics.settings.update', $clinic) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Branding -->
                            <div class="mb-6">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Branding</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Primary Color</label>
                                        <input type="color" name="settings[branding][primary_color]" 
                                            value="{{ $clinic->settings['branding']['primary_color'] ?? '#3B82F6' }}"
                                            class="mt-1 block w-full">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Secondary Color</label>
                                        <input type="color" name="settings[branding][secondary_color]"
                                            value="{{ $clinic->settings['branding']['secondary_color'] ?? '#1E40AF' }}"
                                            class="mt-1 block w-full">
                                    </div>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <div class="mb-6">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Notifications</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="settings[notifications][email]" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ ($clinic->settings['notifications']['email'] ?? true) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900">
                                            Email Notifications
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="settings[notifications][browser]" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ ($clinic->settings['notifications']['browser'] ?? true) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900">
                                            Browser Notifications
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy -->
                            <div class="mb-6">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Privacy</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="settings[privacy][show_members]" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ ($clinic->settings['privacy']['show_members'] ?? true) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900">
                                            Show Members to Public
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="settings[privacy][show_resources]" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ ($clinic->settings['privacy']['show_resources'] ?? false) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900">
                                            Show Resources to Public
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Sharing -->
                            <div class="mb-6">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Sharing</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="settings[sharing][allow_file_sharing]" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ ($clinic->settings['sharing']['allow_file_sharing'] ?? true) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900">
                                            Allow File Sharing
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="settings[sharing][allow_calendar_sharing]" value="1"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ ($clinic->settings['sharing']['allow_calendar_sharing'] ?? true) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900">
                                            Allow Calendar Sharing
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>

<!-- Invite Member Modal -->
<div id="inviteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Invite Member</h3>
                <form action="{{ route('clinics.invitations.store', $clinic) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="doctor">Doctor</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="text-gray-600 hover:text-gray-900" onclick="hideInviteModal()">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Send Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div id="roleModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Role</h3>
                <form id="roleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="new_role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="new_role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="text-gray-600 hover:text-gray-900" onclick="hideRoleModal()">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab functionality
document.querySelectorAll('.tab-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const target = link.getAttribute('href').substring(1);
        
        // Update active tab
        document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        
        // Show target content
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
        document.getElementById(target).classList.remove('hidden');
    });
});

// Modal functionality
function showInviteModal() {
    document.getElementById('inviteModal').classList.remove('hidden');
}

function hideInviteModal() {
    document.getElementById('inviteModal').classList.add('hidden');
}

function showRoleModal(userId) {
    const form = document.getElementById('roleForm');
    form.action = `{{ route('clinics.members.role', $clinic) }}/${userId}`;
    document.getElementById('roleModal').classList.remove('hidden');
}

function hideRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}
</script>
@endpush
@endsection 