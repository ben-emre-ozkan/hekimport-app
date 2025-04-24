<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hekimport') }} - Kayıt Ol</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-teal-50 to-white font-sans">
    <div class="min-h-screen flex flex-col justify-center py-8 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-4xl">🩺</span>
                    <span class="text-3xl font-bold text-teal-600">HEKİMPORT</span>
                </a>
            </div>
            <h2 class="mt-4 text-center text-2xl font-bold text-gray-900">
                Hesap Oluşturun
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Veya
                <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500">
                    mevcut hesabınıza giriş yapın
                </a>
            </p>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white/80 backdrop-blur-sm py-6 px-4 shadow-xl rounded-lg sm:px-8">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                        <ul class="text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-4" action="{{ route('register') }}" method="POST" id="registerForm">
                    @csrf

                    <!-- Role Selection -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input type="radio" id="role_doctor" name="role" value="doctor" class="hidden peer" checked>
                            <label for="role_doctor" class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg cursor-pointer peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:bg-gray-50">
                                <svg class="w-8 h-8 text-gray-500 peer-checked:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div class="mt-2 text-sm font-medium text-gray-900">Diş Hekimi</div>
                                <div class="text-xs text-gray-500">Hasta randevuları için</div>
                            </label>
                        </div>
                        <div>
                            <input type="radio" id="role_patient" name="role" value="patient" class="hidden peer">
                            <label for="role_patient" class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg cursor-pointer peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:bg-gray-50">
                                <svg class="w-8 h-8 text-gray-500 peer-checked:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div class="mt-2 text-sm font-medium text-gray-900">Hasta</div>
                                <div class="text-xs text-gray-500">Randevu almak için</div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Ad Soyad
                            </label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" autocomplete="name" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('name') border-red-300 @enderror"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                E-posta
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('email') border-red-300 @enderror"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Doctor specific fields -->
                    <div id="doctor-fields" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Telefon
                                </label>
                                <div class="mt-1">
                                    <input id="phone" name="phone" type="tel" autocomplete="tel"
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('phone') border-red-300 @enderror"
                                        value="{{ old('phone') }}"
                                        pattern="[0-9]{10}"
                                        maxlength="10"
                                        placeholder="5XX XXX XX XX">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">
                                    Şehir
                                </label>
                                <div class="mt-1">
                                    <select id="city" name="city"
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('city') border-red-300 @enderror">
                                        <option value="">Şehir seçin</option>
                                        @foreach([
                                            'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin', 'Aydın', 'Balıkesir',
                                            'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale', 'Çankırı', 'Çorum', 'Denizli',
                                            'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari',
                                            'Hatay', 'Isparta', 'Mersin', 'İstanbul', 'İzmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir',
                                            'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla', 'Muş', 'Nevşehir',
                                            'Niğde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Tekirdağ', 'Tokat',
                                            'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak', 'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman',
                                            'Kırıkkale', 'Batman', 'Şırnak', 'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük', 'Kilis', 'Osmaniye', 'Düzce'
                                        ] as $city)
                                            <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('city')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Patient specific fields -->
                    <div id="patient-fields" class="hidden space-y-4">
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">
                                Telefon
                            </label>
                            <div class="mt-1">
                                <input id="phone_number" name="phone_number" type="tel" autocomplete="tel"
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('phone_number') border-red-300 @enderror"
                                    value="{{ old('phone_number') }}"
                                    pattern="[0-9]{10}"
                                    maxlength="10"
                                    placeholder="5XX XXX XX XX">
                            </div>
                            @error('phone_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Şifre
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('password') border-red-300 @enderror">
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Şifre Tekrar
                            </label>
                            <div class="mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Kayıt Ol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const doctorFields = document.getElementById('doctor-fields');
            const patientFields = document.getElementById('patient-fields');

            function toggleFields() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                
                if (selectedRole === 'doctor') {
                    doctorFields.classList.remove('hidden');
                    patientFields.classList.add('hidden');
                } else {
                    doctorFields.classList.add('hidden');
                    patientFields.classList.remove('hidden');
                }
            }

            roleInputs.forEach(input => {
                input.addEventListener('change', toggleFields);
            });

            // Initial toggle
            toggleFields();
        });
    </script>
</body>
</html>
