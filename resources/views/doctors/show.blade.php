<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hekimport') }} - Dr. {{ $doctor->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Dr. {{ $doctor->name }}
                    </h1>
                    <a href="{{ route('home') }}" class="text-teal-600 hover:text-teal-500">
                        Ana Sayfa
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Doctor Info -->
                            <div class="md:col-span-2">
                                <div class="flex items-center space-x-4 mb-6">
                                    @if($doctor->profile_image)
                                        <img src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}" class="w-24 h-24 rounded-full object-cover">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-teal-100 flex items-center justify-center">
                                            <span class="text-4xl">👨‍⚕️</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">Dr. {{ $doctor->name }}</h2>
                                        <p class="text-gray-600">{{ $doctor->specialty }}</p>
                                        <p class="text-gray-500">{{ $doctor->clinic->name }}</p>
                                    </div>
                                </div>

                                @if($doctor->bio)
                                    <div class="prose max-w-none mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Hakkında</h3>
                                        <p class="text-gray-600">{{ $doctor->bio }}</p>
                                    </div>
                                @endif

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">İletişim</h3>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Telefon:</span> {{ $doctor->phone }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">E-posta:</span> {{ $doctor->email }}
                                        </p>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Adres</h3>
                                        <p class="text-gray-600">
                                            {{ $doctor->clinic->address }}<br>
                                            {{ $doctor->clinic->city }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Section -->
                            <div class="bg-teal-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Randevu Al</h3>
                                <form action="{{ route('appointments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                    <input type="hidden" name="clinic_id" value="{{ $doctor->clinic_id }}">
                                    
                                    <div class="mb-4">
                                        <label for="appointment_date" class="block text-sm font-medium text-gray-700">Randevu Tarihi</label>
                                        <input type="datetime-local" name="appointment_date" id="appointment_date" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                    </div>

                                    <div class="mb-4">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Notlar</label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                        Randevu Al
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Upcoming Appointments -->
                        @if($doctor->appointments->isNotEmpty())
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Yaklaşan Randevular</h3>
                                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($doctor->appointments as $appointment)
                                            <li class="px-6 py-4">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $appointment->patient->name }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ $appointment->appointment_date->format('d.m.Y H:i') }}
                                                        </p>
                                                    </div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $appointment->status }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 