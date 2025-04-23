<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr. {{ $doctor->name }} - Hekimport</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩺</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Üst menü -->
    <header class="bg-white py-4 px-6 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="flex items-center">
                <span class="text-3xl mr-2">🩺</span>
                <span class="font-bold text-2xl text-teal-600">HEKİMPORT</span>
            </a>
            
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            @if(Auth::user()->isDoctor())
                                <a href="{{ route('doctor.dashboard') }}" class="text-teal-600 hover:text-teal-800">Doktor Paneli</a>
                            @else
                                <a href="{{ route('patient.dashboard') }}" class="text-teal-600 hover:text-teal-800">Hesabım</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-800">Giriş Yap</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-md">Kaydol</a>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div class="container mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <!-- Sol Kısım - Doktor Bilgileri -->
                <div class="md:w-1/3 p-6 bg-teal-50">
                    <div class="flex flex-col items-center">
                        @if($doctor->profile_image)
                            <img class="h-48 w-48 rounded-full object-cover" src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}">
                        @else
                            <div class="h-48 w-48 rounded-full bg-teal-100 flex items-center justify-center">
                                <span class="text-teal-600 font-bold text-5xl">{{ substr($doctor->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h1 class="mt-4 text-2xl font-bold">Dr. {{ $doctor->name }}</h1>
                        <p class="text-gray-600">{{ $doctor->specialty }}</p>
                        
                        <div class="mt-6 w-full space-y-2">
                            @if($doctor->city)
                            <div class="flex justify-between items-center p-2 bg-white rounded shadow-sm">
                                <span class="text-gray-500">Şehir</span>
                                <span class="font-medium">{{ $doctor->city }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center p-2 bg-white rounded shadow-sm">
                                <span class="text-gray-500">E-posta</span>
                                <span class="font-medium">{{ $doctor->email }}</span>
                            </div>
                            
                            @if($doctor->phone)
                            <div class="flex justify-between items-center p-2 bg-white rounded shadow-sm">
                                <span class="text-gray-500">Telefon</span>
                                <span class="font-medium">{{ $doctor->phone }}</span>
                            </div>
                            @endif
                            
                            @if($doctor->address)
                            <div class="flex justify-between items-center p-2 bg-white rounded shadow-sm">
                                <span class="text-gray-500">Adres</span>
                                <span class="font-medium">{{ $doctor->address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sağ Kısım - Randevu Alma ve Biyografi -->
                <div class="md:w-2/3 p-6">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold mb-4">Hakkında</h2>
                        @if($doctor->bio)
                            <p class="text-gray-700">{{ $doctor->bio }}</p>
                        @else
                            <p class="text-gray-500 italic">Biyografi bilgisi bulunmamaktadır.</p>
                        @endif
                    </div>

                    <!-- Randevu Formu -->
                    <div class="bg-teal-50 p-6 rounded-lg">
                        <h2 class="text-xl font-bold mb-4">Randevu Al</h2>
                        
                        <form action="{{ route('appointments.book', $doctor) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <!-- Tarih Seçimi -->
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-700">Randevu Tarihi</label>
                                <input type="date" id="appointment_date" name="appointment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required min="{{ date('Y-m-d') }}">
                            </div>
                            
                            <!-- Saat Seçimi -->
                            <div>
                                <label for="appointment_time" class="block text-sm font-medium text-gray-700">Randevu Saati</label>
                                <select id="appointment_time" name="appointment_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                                    <option value="">Saat Seçiniz</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:30">14:30</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:30">15:30</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:30">16:30</option>
                                </select>
                            </div>
                            
                            <!-- Kişisel Bilgiler -->
                            @guest
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Adınız Soyadınız</label>
                                    <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">E-posta Adresiniz</label>
                                    <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefon Numaranız</label>
                                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                                </div>
                            </div>
                            @endguest
                            
                            <!-- Notlar -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notlar (Opsiyonel)</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200"></textarea>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-md transition">
                                    Randevu Oluştur
                                </button>
                            </div>
                            
                            @guest
                            <p class="text-sm text-gray-600 mt-2">
                                Zaten hesabınız var mı? <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-800">Giriş yapın</a> ve daha hızlı randevu alın.
                            </p>
                            @endguest
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-10 mt-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="/" class="flex items-center">
                        <span class="text-3xl mr-2">🩺</span>
                        <span class="font-bold text-2xl">HEKİMPORT</span>
                    </a>
                    <p class="mt-2 text-gray-400">Sağlığınıza Açılan Modern Kapı</p>
                </div>
                
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-6">
                    <a href="#" class="hover:text-teal-400">Hakkımızda</a>
                    <a href="#" class="hover:text-teal-400">Gizlilik Politikası</a>
                    <a href="#" class="hover:text-teal-400">Kullanım Şartları</a>
                    <a href="#" class="hover:text-teal-400">İletişim</a>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Hekimport. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>
</body>
</html> 