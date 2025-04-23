<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hekimport') }} - Randevu Detayı</title>
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
                        Randevu Detayı
                    </h1>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('patient.appointments') }}" class="text-teal-600 hover:text-teal-500">
                            Randevularım
                        </a>
                        <a href="{{ route('patient.dashboard') }}" class="text-teal-600 hover:text-teal-500">
                            Panel
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-500">
                                Çıkış Yap
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Appointment Details -->
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Randevu Bilgileri</h2>
                                <dl class="grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tarih ve Saat</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->appointment_date->format('d.m.Y H:i') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Durum</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($appointment->status === 'completed') bg-green-100 text-green-800
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800
                                                @endif">
                                                {{ $appointment->status }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Notlar</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->notes ?? 'Not bulunmuyor' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Doctor Details -->
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Doktor Bilgileri</h2>
                                <dl class="grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Ad Soyad</dt>
                                        <dd class="mt-1 text-sm text-gray-900">Dr. {{ $appointment->doctor->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Uzmanlık</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->specialty }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Klinik</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->clinic->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Adres</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->clinic->address }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex justify-end space-x-4">
                            @if($appointment->status === 'scheduled' && $appointment->appointment_date > now())
                                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="return confirm('Randevuyu iptal etmek istediğinizden emin misiniz?')">
                                        Randevuyu İptal Et
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('patient.appointments') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 