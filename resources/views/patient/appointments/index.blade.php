<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hekimport') }} - Randevularım</title>
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
                        Randevularım
                    </h1>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('patient.dashboard') }}" class="text-teal-600 hover:text-teal-500">
                            Panel
                        </a>
                        <a href="{{ route('patient.profile.edit') }}" class="text-teal-600 hover:text-teal-500">
                            Profil
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
                <!-- Filters -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
                    <div class="p-6">
                        <form action="{{ route('patient.appointments') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
                                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">Tümü</option>
                                        <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Planlandı</option>
                                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Tarih</label>
                                    <input type="date" id="date" name="date" value="{{ $date }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                </div>

                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">Arama</label>
                                    <input type="text" id="search" name="search" value="{{ $search }}"
                                        placeholder="Doktor adı veya uzmanlık"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                </div>

                                <div class="flex items-end">
                                    <button type="submit"
                                        class="w-full bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                        Filtrele
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Appointments List -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        @if($appointments->isEmpty())
                            <p class="text-gray-500 text-center">Randevu bulunamadı.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih ve Saat</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doktor</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klinik</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($appointments as $appointment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $appointment->appointment_date->format('d.m.Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">Dr. {{ $appointment->doctor->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $appointment->doctor->specialty }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $appointment->doctor->clinic->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($appointment->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                        @else bg-blue-100 text-blue-800
                                                        @endif">
                                                        {{ $appointment->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="text-teal-600 hover:text-teal-900">Detay</a>
                                                    @if($appointment->status === 'scheduled' && $appointment->appointment_date > now())
                                                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="inline-block ml-2">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Randevuyu iptal etmek istediğinizden emin misiniz?')">
                                                                İptal Et
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $appointments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 