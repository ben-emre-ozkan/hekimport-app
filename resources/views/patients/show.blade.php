<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasta Detayları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:space-x-8">
                        <div class="md:w-1/3 mb-6 md:mb-0">
                            <div class="flex flex-col">
                                <div class="h-32 w-32 rounded-full bg-green-100 flex items-center justify-center mx-auto md:mx-0">
                                    <span class="text-green-600 font-bold text-4xl">{{ substr($patient->name, 0, 1) }}</span>
                                </div>
                                <h3 class="mt-4 text-2xl font-semibold text-gray-900 text-center md:text-left">{{ $patient->name }}</h3>
                                
                                <div class="mt-6 w-full">
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">E-posta</span>
                                        <span class="text-gray-900">{{ $patient->email }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Telefon</span>
                                        <span class="text-gray-900">{{ $patient->phone ?? 'Belirtilmemiş' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Doğum Tarihi</span>
                                        <span class="text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('d.m.Y') : 'Belirtilmemiş' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Cinsiyet</span>
                                        <span class="text-gray-900">
                                            @if($patient->gender == 'male')
                                                Erkek
                                            @elseif($patient->gender == 'female')
                                                Kadın
                                            @elseif($patient->gender == 'other')
                                                Diğer
                                            @else
                                                Belirtilmemiş
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <span class="text-gray-500">Acil Durum İletişim</span>
                                        <span class="text-gray-900">{{ $patient->emergency_contact ?? 'Belirtilmemiş' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2">
                                        <span class="text-gray-500">Kayıt Tarihi</span>
                                        <span class="text-gray-900">{{ $patient->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:w-2/3">
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Adres</h4>
                                <p class="text-gray-700">{{ $patient->address ?? 'Adres bilgisi bulunmamaktadır.' }}</p>
                            </div>
                            
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Tıbbi Geçmiş</h4>
                                <p class="text-gray-700">{{ $patient->medical_history ?? 'Tıbbi geçmiş bilgisi bulunmamaktadır.' }}</p>
                            </div>
                            
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Randevu Geçmişi</h4>
                                
                                @if($appointments->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih ve Saat</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doktor</th>
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
                                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900">Görüntüle</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500">Randevu geçmişi bulunmamaktadır.</p>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                    Geri Dön
                                </a>
                                <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                    Randevu Oluştur
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                    Düzenle
                                </a>
                                <form class="inline-block" action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Bu hastayı silmek istediğinizden emin misiniz?');">
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