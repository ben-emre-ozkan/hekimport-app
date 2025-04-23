<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Randevu Onayı') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="max-w-2xl mx-auto">
                        <div class="text-center mb-8">
                            <h3 class="text-lg font-medium text-gray-900">Randevu Detayları</h3>
                            <p class="mt-1 text-sm text-gray-500">Lütfen randevu detaylarını kontrol edin ve onaylayın.</p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Doktor</dt>
                                    <dd class="mt-1 text-sm text-gray-900">Dr. {{ $appointment->doctor->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uzmanlık</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->specialty }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tarih</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->appointment_date->format('d.m.Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Saat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->appointment_date->format('H:i') }}</dd>
                                </div>
                                @if($appointment->notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Notlar</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('appointments.confirm', $appointment) }}" 
                               class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Randevuyu Onayla
                            </a>
                            <a href="{{ route('appointments.cancel', $appointment) }}" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                İptal Et
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 