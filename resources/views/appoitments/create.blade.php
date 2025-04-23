<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Yeni Randevu Oluştur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Doctor -->
                            <div>
                                <x-input-label for="doctor_id" :value="__('Doktor')" />
                                <select id="doctor_id" name="doctor_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Doktor Seçiniz</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id || (request('doctor_id') == $doctor->id) ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} ({{ $doctor->specialty }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                            </div>

                            <!-- Patient -->
                            <div>
                                <x-input-label for="patient_id" :value="__('Hasta')" />
                                <select id="patient_id" name="patient_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Hasta Seçiniz</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id || (request('patient_id') == $patient->id) ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->phone ?? 'Telefon Yok' }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                                
                                <div class="mt-2">
                                    <a href="{{ route('patients.create') }}" class="text-sm text-blue-600 hover:text-blue-900">
                                        + Yeni hasta ekle
                                    </a>
                                </div>
                            </div>

                            <!-- Appointment Date -->
                            <div>
                                <x-input-label for="appointment_date" :value="__('Randevu Tarihi')" />
                                <x-text-input id="appointment_date" class="block mt-1 w-full" type="date" name="appointment_date" :value="old('appointment_date')" required />
                                <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                            </div>

                            <!-- Appointment Time -->
                            <div>
                                <x-input-label for="appointment_time" :value="__('Randevu Saati')" />
                                <x-text-input id="appointment_time" class="block mt-1 w-full" type="time" name="appointment_time" :value="old('appointment_time')" required />
                                <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notlar')" />
                                <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                İptal
                            </a>
                            <x-primary-button>
                                {{ __('Oluştur') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Bugünün tarihini al ve minimum tarih olarak ayarla
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('appointment_date').setAttribute('min', today);
        });
    </script>
</x-app-layout>