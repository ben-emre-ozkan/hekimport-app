<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Doktor Dashboard') }}
            </h2>
            <a href="{{ route('doctor.patients.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add Patient') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Toplam Randevu -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-teal-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm">Toplam Randevu</p>
                                <p class="text-2xl font-semibold text-gray-700">{{ $appointmentsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bugünkü Randevular -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm">Bugünkü Randevular</p>
                                <p class="text-2xl font-semibold text-gray-700">{{ $todayAppointmentsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Toplam Hasta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-gray-500 text-sm">Toplam Hasta</p>
                                <p class="text-2xl font-semibold text-gray-700">{{ $patientsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bugünkü Randevular Listesi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bugünkü Randevular</h3>
                    
                    @if($todayAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saat</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasta</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İletişim</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $appointment->appointment_date->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $appointment->patient->phone }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->patient->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($appointment->status == 'scheduled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Planlandı
                                                    </span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Tamamlandı
                                                    </span>
                                                @elseif($appointment->status == 'cancelled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        İptal Edildi
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-teal-600 hover:text-teal-900 mr-3">Görüntüle</a>
                                                
                                                @if($appointment->status == 'scheduled')
                                                    <form class="inline-block" action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Tamamla</button>
                                                    </form>
                                                    
                                                    <form class="inline-block" action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="text-red-600 hover:text-red-900">İptal Et</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Bugün için planlanmış randevu bulunmamaktadır.</p>
                    @endif
                    
                    <div class="mt-4 text-right">
                        <a href="{{ route('doctor.appointments.index') }}" class="text-teal-600 hover:text-teal-900">
                            Tüm randevuları görüntüle &rarr;
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Yaklaşan Randevular -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Yaklaşan Randevular</h3>
                    
                    @if($upcomingAppointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih ve Saat</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasta</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İletişim</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($upcomingAppointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $appointment->appointment_date->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $appointment->patient->phone ?? 'Belirtilmemiş' }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->patient->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-teal-600 hover:text-teal-900">Görüntüle</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Yaklaşan randevu bulunmamaktadır.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 