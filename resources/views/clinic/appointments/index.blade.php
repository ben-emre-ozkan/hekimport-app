@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
        <a href="{{ route('clinic.appointments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            New Appointment
        </a>
    </div>

    <!-- View Toggle -->
    <div class="mb-6">
        <div class="flex space-x-4">
            <button id="calendarView" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Calendar</button>
            <button id="listView" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700">List</button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date Range</label>
                <div class="flex space-x-2">
                    <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Doctor</label>
                <select name="doctor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Calendar View -->
    <div id="calendarContainer" class="bg-white rounded-lg shadow p-4">
        <div id="calendar"></div>
    </div>

    <!-- List View -->
    <div id="listContainer" class="bg-white rounded-lg shadow p-4 hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $appointment->doctor->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $appointment->start_time->format('M d, Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($appointment->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($appointment->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('clinic.appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="{{ route('clinic.appointments.edit', $appointment) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('clinic.appointments.destroy', $appointment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this appointment?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle
    const calendarView = document.getElementById('calendarView');
    const listView = document.getElementById('listView');
    const calendarContainer = document.getElementById('calendarContainer');
    const listContainer = document.getElementById('listContainer');

    calendarView.addEventListener('click', () => {
        calendarContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
        calendarView.classList.add('bg-blue-600', 'text-white');
        calendarView.classList.remove('bg-gray-200', 'text-gray-700');
        listView.classList.add('bg-gray-200', 'text-gray-700');
        listView.classList.remove('bg-blue-600', 'text-white');
    });

    listView.addEventListener('click', () => {
        listContainer.classList.remove('hidden');
        calendarContainer.classList.add('hidden');
        listView.classList.add('bg-blue-600', 'text-white');
        listView.classList.remove('bg-gray-200', 'text-gray-700');
        calendarView.classList.add('bg-gray-200', 'text-gray-700');
        calendarView.classList.remove('bg-blue-600', 'text-white');
    });

    // Calendar Initialization
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: {
            url: '{{ route("clinic.appointments.index") }}',
            method: 'GET',
            extraParams: function() {
                return {
                    view: 'calendar',
                    start_date: document.querySelector('input[name="start_date"]').value,
                    end_date: document.querySelector('input[name="end_date"]').value,
                    doctor_id: document.querySelector('select[name="doctor_id"]').value,
                    status: document.querySelector('select[name="status"]').value
                };
            }
        },
        eventClick: function(info) {
            window.location.href = '{{ route("clinic.appointments.show", "") }}/' + info.event.id;
        }
    });
    calendar.render();

    // Filter Form Submission
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        calendar.refetchEvents();
        // For list view, we'll need to reload the page with the filter parameters
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        window.location.href = '{{ route("clinic.appointments.index") }}?' + params.toString();
    });
});
</script>
@endpush
@endsection 