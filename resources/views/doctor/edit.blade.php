<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doktor Düzenle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('doctor.update', $doctor) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('İsim')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $doctor->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Specialty -->
                            <div>
                                <x-input-label for="specialty" :value="__('Uzmanlık')" />
                                <x-text-input id="specialty" class="block mt-1 w-full" type="text" name="specialty" :value="old('specialty', $doctor->specialty)" required />
                                <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" :value="__('E-posta')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $doctor->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Telefon')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $doctor->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Bio -->
                            <div class="md:col-span-2">
                                <x-input-label for="bio" :value="__('Biyografi')" />
                                <textarea id="bio" name="bio" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('bio', $doctor->bio) }}</textarea>
                                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                            </div>

                            <!-- Profile Image -->
                            <div class="md:col-span-2">
                                <x-input-label for="profile_image" :value="__('Profil Fotoğrafı')" />
                                
                                @if($doctor->profile_image)
                                    <div class="mt-2 mb-4">
                                        <img src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}" class="h-32 w-32 object-cover rounded-full">
                                        <p class="mt-1 text-sm text-gray-500">Mevcut fotoğraf</p>
                                    </div>
                                @endif
                                
                                <input id="profile_image" name="profile_image" type="file" class="block mt-1 w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" accept="image/*" />
                                <p class="mt-1 text-sm text-gray-500">JPG, PNG ya da GIF. Maksimum 2MB. Yeni bir fotoğraf seçilmezse mevcut fotoğraf korunacaktır.</p>
                                <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <label for="is_active" class="inline-flex items-center mt-3">
                                    <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $doctor->is_active ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Aktif') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('doctor.show', $doctor) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('İptal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Güncelle') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>