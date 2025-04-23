<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doktor Detayları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:space-x-8">
                        <div class="md:w-1/3 mb-6 md:mb-0">
                            <div class="flex flex-col items-center">
                                @if($doctor->profile_image)
                                    <img class="h-48 w-48 rounded-full object-cover" src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}">
                                @else
                                    <div class="h-48 w-48 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-5xl">{{ substr($doctor->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h3 class="mt-4 text-2xl font-semibold text-gray-900">Dr. {{ $doctor->name }}</h3>
                                <p class="text-gray-600">{{ $doctor->specialty }}</p>
                                
                                <div class="mt-6 w-full">
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Durum</span>
                                        @if($doctor->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Pasif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">E-posta</span>
                                        <span class="text-gray-900">{{ $doctor->email }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Telefon</span>
                                        <span class="text-gray-900">{{ $doctor->phone ?? 'Belirtilmemiş' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Randevu Sayısı</span>
                                        <span class="text-gray-900">{{ $doctor->appointments->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2">
                                        <span class="text-gray-500">Kayıt Tarihi</span>
                                        <span class="text-gray-900">{{ $doctor->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:w-2/3">
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Biyografi</h4>
                                <p class="text-gray-700">{{ $doctor->bio ?? 'Biyografi bilgisi bulunmamaktadır.' }}</p>
                            </div>
                            
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Yaklaşan Randevular</h4>
                                @php
                                    $upcomingAppointments = $doctor->appointments()
                                        ->with('patient')
                                        ->where('appointment_date', '>=', now())
                                        ->where('status', 'scheduled')
                                        ->orderBy('appointment_date')
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @if($upcomingAppointments->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih ve Saat</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasta</th>
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
                                                            <div class="text-sm text-gray-500">{{ $appointment->patient->phone }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900">Görüntüle</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-4 text-right">
                                        <a href="{{ route('appointments.index') }}?doctor_id={{ $doctor->id }}" class="text-blue-600 hover:text-blue-900">Tüm randevuları görüntüle &rarr;</a>
                                    </div>
                                @else
                                    <p class="text-gray-500">Yaklaşan randevu bulunmamaktadır.</p>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('doctor.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Geri Dön') }}
                                </a>
                                <a href="{{ route('doctor.edit', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Düzenle') }}
                                </a>
                                <form class="inline-block" action="{{ route('doctor.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Bu doktoru silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>