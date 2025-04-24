<header class="bg-white shadow">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('vitrin.home', ['subdomain' => $vitrin->subdomain]) }}" class="text-xl font-bold text-gray-800">
                    {{ $vitrin->name }}
                </a>
            </div>

            <div class="hidden md:flex space-x-8">
                <a href="{{ route('vitrin.home', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('vitrin.home') ? 'text-gray-900 font-medium' : '' }}">
                    Home
                </a>
                <a href="{{ route('vitrin.about', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('vitrin.about') ? 'text-gray-900 font-medium' : '' }}">
                    About
                </a>
                <a href="{{ route('vitrin.blog', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('vitrin.blog*') ? 'text-gray-900 font-medium' : '' }}">
                    Blog
                </a>
                <a href="{{ route('vitrin.contact', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('vitrin.contact') ? 'text-gray-900 font-medium' : '' }}">
                    Contact
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900" 
                        x-data="{ open: false }" 
                        @click="open = !open">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-show="open" @click.away="open = false">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('vitrin.home', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Home
                </a>
                <a href="{{ route('vitrin.about', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    About
                </a>
                <a href="{{ route('vitrin.blog', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Blog
                </a>
                <a href="{{ route('vitrin.contact', ['subdomain' => $vitrin->subdomain]) }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    Contact
                </a>
            </div>
        </div>
    </nav>
</header> 