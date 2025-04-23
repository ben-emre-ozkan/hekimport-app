<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Hekimport - Sağlığınıza Açılan Modern Kapı</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩺</text></svg>">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Inter', sans-serif;
                overflow-x: hidden;
            }
            .font-orbitron {
                font-family: 'Orbitron', sans-serif;
            }
            #background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                background: linear-gradient(135deg, #00c8b3, #0099e5, #f3f4f6, #0077c8);
                background-size: 400%;
                animation: gradientFlow 15s ease infinite;
            }
            @keyframes gradientFlow {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .hero-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            .feature-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }
            .doctor-card {
                transition: transform 0.3s ease;
            }
            .doctor-card:hover {
                transform: translateY(-5px);
            }
            .nav-link {
                position: relative;
            }
            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: -2px;
                left: 0;
                background-color: #00c8b3;
                transition: width 0.3s ease;
            }
            .nav-link:hover::after {
                width: 100%;
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="background"></div>
        
        <div class="relative flex flex-col min-h-screen">
            <!-- Üst menü -->
            <header class="bg-white bg-opacity-90 py-4 px-6 shadow-md backdrop-blur-sm fixed w-full z-50">
                <div class="container mx-auto flex justify-between items-center">
                    <a href="/" class="flex items-center">
                        <span class="text-3xl mr-2">🩺</span>
                        <span class="font-orbitron text-2xl text-teal-600">HEKİMPORT</span>
                    </a>
                    
                    <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            <div class="flex items-center space-x-6">
                                @auth
                                    @if(Auth::user()->isDoctor())
                                        <a href="{{ route('doctor.dashboard') }}" class="nav-link text-teal-600 hover:text-teal-800">Doktor Paneli</a>
                                    @else
                                        <a href="{{ route('patient.dashboard') }}" class="nav-link text-teal-600 hover:text-teal-800">Hesabım</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="nav-link text-teal-600 hover:text-teal-800">Giriş Yap</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-full transition duration-300 ease-in-out transform hover:scale-105 shadow-md">Kaydol</a>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Hero Bölümü -->
            <section class="pt-32 pb-16 flex-grow flex items-center hero-pattern">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 mb-10 md:mb-0" x-data="{ show: false }" x-init="show = true">
                            <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6" 
                                x-show="show" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-x-12"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                Sağlık Randevunuzu<br>Hemen Alın
                            </h1>
                            <p class="text-xl md:text-2xl text-white mb-8 opacity-90" 
                               x-show="show" 
                               x-transition:enter="transition ease-out duration-300"
                               x-transition:enter-start="opacity-0 transform -translate-x-12"
                               x-transition:enter-end="opacity-100 transform translate-x-0"
                               x-transition:delay="100">
                                Uzman doktorlarımızla sağlığınıza kavuşun. Şehrinize göre doktor ve klinik bulun.
                            </p>
                            
                            <!-- Doktor Arama Formu -->
                            <div class="bg-white p-8 rounded-2xl shadow-xl" 
                                 x-show="show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-x-12"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 x-transition:delay="200">
                                <form action="{{ route('doctor.search') }}" method="GET" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="specialty" class="block text-sm font-medium text-gray-700 mb-2">Uzmanlık</label>
                                            <select id="specialty" name="specialty" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                                <option value="">Tüm Uzmanlıklar</option>
                                                <option value="Dahiliye">Dahiliye</option>
                                                <option value="Kardiyoloji">Kardiyoloji</option>
                                                <option value="Nöroloji">Nöroloji</option>
                                                <option value="Ortopedi">Ortopedi</option>
                                                <option value="Göz Hastalıkları">Göz Hastalıkları</option>
                                                <option value="Diş Hekimliği">Diş Hekimliği</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Şehir</label>
                                            <select id="city" name="city" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                                <option value="">Tüm Şehirler</option>
                                                <option value="İstanbul">İstanbul</option>
                                                <option value="Ankara">Ankara</option>
                                                <option value="İzmir">İzmir</option>
                                                <option value="Bursa">Bursa</option>
                                                <option value="Antalya">Antalya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                                            Doktor Bul
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="md:w-1/2 flex justify-center" x-data="{ show: false }" x-init="show = true">
                            <div class="relative w-full max-w-lg" 
                                 x-show="show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-x-12"
                                 x-transition:enter-end="opacity-100 transform translate-x-0">
                                <img src="{{ asset('img/hekimport_acilis.jpg') }}" 
                                    alt="Hekimport Açılış Görseli" 
                                    class="rounded-2xl shadow-2xl w-full h-auto object-cover transform hover:scale-105 transition duration-300"
                                    loading="lazy"
                                    width="800"
                                    height="600"
                                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 800 600\'%3E%3Crect width=\'800\' height=\'600\' fill=\'%23f3f4f6\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'24\' fill=\'%236b7280\'%3EGörsel Yüklenemedi%3C/text%3E%3C/svg%3E';">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Öne Çıkan Doktorlar -->
            <section class="py-20 bg-white">
                <div class="container mx-auto px-6">
                    <h2 class="text-4xl font-bold text-center mb-12">Öne Çıkan Doktorlarımız</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($doctors as $doctor)
                        <div class="doctor-card bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-center mb-6">
                                    @if($doctor->profile_image)
                                        <img src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-teal-100">
                                    @else
                                        <div class="w-32 h-32 rounded-full bg-teal-100 flex items-center justify-center border-4 border-teal-200">
                                            <span class="text-5xl">👨‍⚕️</span>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-xl font-semibold text-center mb-2">Dr. {{ $doctor->name }}</h3>
                                <p class="text-gray-600 text-center mb-6">{{ $doctor->specialty }}</p>
                                <div class="flex justify-center">
                                    <a href="{{ route('doctor.public-profile', $doctor) }}" class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-full transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                                        {{ __('Profili Görüntüle') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-12">
                        <a href="{{ route('doctor.search') }}" class="inline-flex items-center text-teal-600 hover:text-teal-800 font-semibold group">
                            {{ __('Tüm doktorları görüntüle') }}
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Özellikler -->
            <section class="py-20 bg-gray-50">
                <div class="container mx-auto px-6">
                    <h2 class="text-4xl font-bold text-center mb-12">Neden Bizi Tercih Etmelisiniz?</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                            <div class="text-5xl text-teal-500 mb-6">⏱️</div>
                            <h3 class="text-2xl font-semibold mb-4">Hızlı Randevu</h3>
                            <p class="text-gray-600 leading-relaxed">Saniyeler içinde en uygun doktor ve saati seçerek hızlıca randevu alın. Zamanınızı değerli kılın.</p>
                        </div>
                        
                        <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                            <div class="text-5xl text-teal-500 mb-6">👨‍⚕️</div>
                            <h3 class="text-2xl font-semibold mb-4">Uzman Doktorlar</h3>
                            <p class="text-gray-600 leading-relaxed">Alanında uzman doktorlarımız ile kaliteli sağlık hizmeti alın. Güvenilir ve deneyimli kadromuz yanınızda.</p>
                        </div>
                        
                        <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                            <div class="text-5xl text-teal-500 mb-6">📱</div>
                            <h3 class="text-2xl font-semibold mb-4">Kolay Erişim</h3>
                            <p class="text-gray-600 leading-relaxed">7/24 online platform üzerinden tüm işlemlerinizi kolayca gerçekleştirin. Sağlığınız için her zaman ulaşılabiliriz.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-gray-900 text-white py-12">
                <div class="container mx-auto px-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <a href="/" class="flex items-center mb-4">
                                <span class="text-3xl mr-2">🩺</span>
                                <span class="font-orbitron text-2xl">HEKİMPORT</span>
                            </a>
                            <p class="text-gray-400 mb-4">Sağlığınıza Açılan Modern Kapı</p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-teal-400 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-teal-400 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-teal-400 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Hızlı Bağlantılar</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-teal-400 transition">Hakkımızda</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-teal-400 transition">Gizlilik Politikası</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-teal-400 transition">Kullanım Şartları</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-teal-400 transition">İletişim</a></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4">İletişim</h3>
                            <ul class="space-y-2">
                                <li class="flex items-center text-gray-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    info@hekimport.com
                                </li>
                                <li class="flex items-center text-gray-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    +90 (212) 123 45 67
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                        <p>&copy; {{ date('Y') }} Hekimport. Tüm hakları saklıdır.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>