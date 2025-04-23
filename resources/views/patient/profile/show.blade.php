<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Bilgilerim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Temel Bilgiler -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Temel Bilgiler') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('İsim') }}</p>
                                <p class="mt-1">{{ $patient->name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('E-posta') }}</p>
                                <p class="mt-1">{{ $patient->email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Telefon') }}</p>
                                <p class="mt-1">{{ $patient->phone }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Doğum Tarihi') }}</p>
                                <p class="mt-1">{{ $patient->birth_date->format('d.m.Y') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Cinsiyet') }}</p>
                                <p class="mt-1">{{ ucfirst($patient->gender) }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Kan Grubu') }}</p>
                                <p class="mt-1">{{ $patient->blood_type }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Adres Bilgileri -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Adres Bilgileri') }}</h3>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Adres') }}</p>
                            <p class="mt-1">{{ $patient->address }}</p>
                        </div>
                    </div>

                    <!-- Sağlık Bilgileri -->
                    <div class="mb-6 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Sağlık Bilgileri') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Alerjiler') }}</p>
                                <p class="mt-1">{{ $patient->allergies ?: 'Belirtilmemiş' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Kronik Hastalıklar') }}</p>
                                <p class="mt-1">{{ $patient->chronic_diseases ?: 'Belirtilmemiş' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Kullanılan İlaçlar') }}</p>
                                <p class="mt-1">{{ $patient->current_medications ?: 'Belirtilmemiş' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('patient.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Düzenle') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 