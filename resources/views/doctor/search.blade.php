<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doktor Arama - Hekimport</title>
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
        <h1 class="text-3xl font-bold mb-8">Doktor Ara</h1>
        
        <!-- Arama Formu -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form action="{{ route('doctor.search') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="specialty" class="block text-sm font-medium text-gray-700">Uzmanlık</label>
                        <select id="specialty" name="specialty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            <option value="">Tüm Uzmanlıklar</option>
                            <option value="Dahiliye" {{ $specialty == 'Dahiliye' ? 'selected' : '' }}>Dahiliye</option>
                            <option value="Kardiyoloji" {{ $specialty == 'Kardiyoloji' ? 'selected' : '' }}>Kardiyoloji</option>
                            <option value="Nöroloji" {{ $specialty == 'Nöroloji' ? 'selected' : '' }}>Nöroloji</option>
                            <option value="Ortopedi" {{ $specialty == 'Ortopedi' ? 'selected' : '' }}>Ortopedi</option>
                            <option value="Göz Hastalıkları" {{ $specialty == 'Göz Hastalıkları' ? 'selected' : '' }}>Göz Hastalıkları</option>
                            <option value="Diş Hekimliği" {{ $specialty == 'Diş Hekimliği' ? 'selected' : '' }}>Diş Hekimliği</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Şehir</label>
                        <select id="city" name="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            <option value="">Tüm Şehirler</option>
                            <option value="İstanbul" {{ $city == 'İstanbul' ? 'selected' : '' }}>İstanbul</option>
                            <option value="Ankara" {{ $city == 'Ankara' ? 'selected' : '' }}>Ankara</option>
                            <option value="İzmir" {{ $city == 'İzmir' ? 'selected' : '' }}>İzmir</option>
                            <option value="Bursa" {{ $city == 'Bursa' ? 'selected' : '' }}>Bursa</option>
                            <option value="Antalya" {{ $city == 'Antalya' ? 'selected' : '' }}>Antalya</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-md transition">
                            Ara
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Sonuçlar -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($doctors as $doctor)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if($doctor->profile_image)
                                <img class="h-16 w-16 rounded-full object-cover mr-4" src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-teal-100 flex items-center justify-center mr-4">
                                    <span class="text-teal-600 font-bold text-xl">{{ substr($doctor->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold">Dr. {{ $doctor->name }}</h3>
                                <p class="text-gray-600">{{ $doctor->specialty }}</p>
                                @if($doctor->city)
                                    <p class="text-gray-500 text-sm">{{ $doctor->city }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="border-t pt-4 mt-4">
                            <a href="{{ route('doctor.public-profile', $doctor) }}" class="block w-full bg-teal-500 hover:bg-teal-600 text-white text-center px-4 py-2 rounded-md transition">
                                {{ __('Profili Görüntüle') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 rounded-lg shadow-md text-center">
                    <p class="text-lg text-gray-600 mb-4">Arama kriterlerinize uygun doktor bulunamadı.</p>
                    <a href="{{ route('doctor.search') }}" class="text-teal-600 hover:text-teal-800 font-semibold">
                        {{ __('Tüm doktorları görüntüle') }}
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $doctors->links() }}
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