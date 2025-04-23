<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Bilgilerimi Düzenle') }}
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

                    <form method="POST" action="{{ route('patient.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Temel Bilgiler -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Temel Bilgiler') }}</h3>
                            
                            <!-- İsim -->
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('İsim')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $patient->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- E-posta -->
                            <div class="mb-4">
                                <x-input-label for="email" :value="__('E-posta')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $patient->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Telefon -->
                            <div class="mb-4">
                                <x-input-label for="phone" :value="__('Telefon')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $patient->phone)" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Doğum Tarihi -->
                            <div class="mb-4">
                                <x-input-label for="birth_date" :value="__('Doğum Tarihi')" />
                                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" 
                                    :value="old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '')" 
                                    required />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>

                            <!-- Cinsiyet -->
                            <div class="mb-4">
                                <x-input-label for="gender" :value="__('Cinsiyet')" />
                                <select id="gender" name="gender" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                                    <option value="">Cinsiyet Seçiniz</option>
                                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Erkek</option>
                                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Kadın</option>
                                    <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Diğer</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>

                            <!-- Kan Grubu -->
                            <div class="mb-4">
                                <x-input-label for="blood_type" :value="__('Kan Grubu')" />
                                <select id="blood_type" name="blood_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                                    <option value="">Kan Grubu Seçiniz</option>
                                    <option value="A+" {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <option value="0+" {{ old('blood_type', $patient->blood_type) == '0+' ? 'selected' : '' }}>0+</option>
                                    <option value="0-" {{ old('blood_type', $patient->blood_type) == '0-' ? 'selected' : '' }}>0-</option>
                                </select>
                                <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Adres Bilgileri -->
                        <div class="mb-6 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Adres Bilgileri') }}</h3>
                            
                            <!-- Adres -->
                            <div class="mb-4">
                                <x-input-label for="address" :value="__('Adres')" />
                                <textarea id="address" name="address" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>{{ old('address', $patient->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Sağlık Bilgileri -->
                        <div class="mb-6 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Sağlık Bilgileri') }}</h3>
                            
                            <!-- Alerjiler -->
                            <div class="mb-4">
                                <x-input-label for="allergies" :value="__('Alerjiler')" />
                                <textarea id="allergies" name="allergies" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">{{ old('allergies', $patient->allergies) }}</textarea>
                                <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                            </div>
                            
                            <!-- Kronik Hastalıklar -->
                            <div class="mb-4">
                                <x-input-label for="chronic_diseases" :value="__('Kronik Hastalıklar')" />
                                <textarea id="chronic_diseases" name="chronic_diseases" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                                <x-input-error :messages="$errors->get('chronic_diseases')" class="mt-2" />
                            </div>
                            
                            <!-- Kullanılan İlaçlar -->
                            <div class="mb-4">
                                <x-input-label for="current_medications" :value="__('Kullanılan İlaçlar')" />
                                <textarea id="current_medications" name="current_medications" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">{{ old('current_medications', $patient->current_medications) }}</textarea>
                                <x-input-error :messages="$errors->get('current_medications')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Kaydet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 