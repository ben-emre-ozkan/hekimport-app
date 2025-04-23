<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Randevu Detayları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/2 mb-6 md:mb-0 md:pr-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Randevu Bilgileri</h3>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Randevu No</p>
                                            <p class="font-medium">#{{ $appointment->id }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Durum</p>
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
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Randevu Tarihi</p>
                                            <p class="font-medium">{{ $appointment->appointment_date->format('d.m.Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Randevu Saati</p>
                                            <p class="font-medium">{{ $appointment->appointment_date->format('H:i') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Oluşturma Tarihi</p>
                                            <p class="font-medium">{{ $appointment->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Son Güncelleme</p>
                                            <p class="font-medium">{{ $appointment->updated_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Notlar</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p>{{ $appointment->notes ?? 'Not bulunmamaktadır.' }}</p>
                                </div>
                            </div>
                            
                            @if($appointment->status == 'scheduled' && $appointment->appointment_date >= now())
                                <div class="flex space-x-2 mb-6" x-data="{}" data-appointment-id="{{ $appointment->id }}">
                                    <button @click="fetch('{{ route('appointments.update-status', $appointment) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ status: 'completed' })
                                    }).then(() => window.location.reload())" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Randevuyu Tamamla
                                    </button>
                                    
                                    <button @click="fetch('{{ route('appointments.update-status', $appointment) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ status: 'cancelled' })
                                    }).then(() => window.location.reload())" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Randevuyu İptal Et
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <div class="md:w-1/2 md:border-l md:pl-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Doktor Bilgileri</h3>
                                
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        @if($appointment->doctor->profile_image)
                                            <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $appointment->doctor->profile_image) }}" alt="{{ $appointment->doctor->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-bold">{{ substr($appointment->doctor->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold">Dr. {{ $appointment->doctor->name }}</h4>
                                        <p class="text-gray-600">{{ $appointment->doctor->specialty }}</p>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <p class="text-sm text-gray-500">E-posta</p>
                                            <p class="font-medium">{{ $appointment->doctor->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Telefon</p>
                                            <p class="font-medium">{{ $appointment->doctor->phone ?? 'Belirtilmemiş' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('doctors.show', $appointment->doctor) }}" class="text-blue-600 hover:text-blue-900">
                                        Doktor profilini görüntüle &rarr;
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Hasta Bilgileri</h3>
                                
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-green-600 font-bold">{{ substr($appointment->patient->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold">{{ $appointment->patient->name }}</h4>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <p class="text-sm text-gray-500">E-posta</p>
                                            <p class="font-medium">{{ $appointment->patient->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Telefon</p>
                                            <p class="font-medium">{{ $appointment->patient->phone ?? 'Belirtilmemiş' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Doğum Tarihi</p>
                                            <p class="font-medium">{{ $appointment->patient->date_of_birth ? $appointment->patient->date_of_birth->format('d.m.Y') : 'Belirtilmemiş' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('patients.show', $appointment->patient) }}" class="text-blue-600 hover:text-blue-900">
                                        Hasta profilini görüntüle &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6 pt-6 border-t">
                        <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                            Geri Dön
                        </a>
                        <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                            Düzenle
                        </a>
                        <form class="inline-block" action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Bu randevuyu silmek istediğinizden emin misiniz?');">
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
</x-app-layout>