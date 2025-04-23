<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Randevunuz Onaylandı!</h2>
                        <p class="text-gray-600 mt-2">Randevu detaylarınız aşağıda yer almaktadır.</p>
                    </div>

                    <div class="max-w-2xl mx-auto">
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4">Randevu Bilgileri</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Doktor:</span>
                                    <span class="font-medium">Dr. {{ $appointment->doctor->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Uzmanlık:</span>
                                    <span class="font-medium">{{ $appointment->doctor->specialty }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tarih:</span>
                                    <span class="font-medium">{{ $appointment->appointment_date->format('d.m.Y') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Saat:</span>
                                    <span class="font-medium">{{ $appointment->appointment_date->format('H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Durum:</span>
                                    <span class="font-medium text-green-600">Onaylandı</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Hasta Bilgileri</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ad Soyad:</span>
                                    <span class="font-medium">{{ $appointment->patient->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">E-posta:</span>
                                    <span class="font-medium">{{ $appointment->patient->email }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Telefon:</span>
                                    <span class="font-medium">{{ $appointment->patient->phone }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <a href="{{ route('doctors.public-profile', $appointment->doctor) }}" class="inline-block bg-teal-600 text-white px-6 py-3 rounded-md hover:bg-teal-700 transition">
                                Doktor Profiline Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 