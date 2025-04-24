<!-- Sidebar for mobile -->
<div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen">
    <div class="fixed inset-0 bg-gray-900/80" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-0 flex">
        <div class="relative mr-16 flex w-full max-w-xs flex-1" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar component for mobile -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}">
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{ route('masa.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('masa.index') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('masa.index') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        Kontrol Paneli
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('masa.vitrin.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('masa.vitrin.*') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('masa.vitrin.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                        </svg>
                                        Vitrin Yönetimi
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('masa.clinic.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('masa.clinic.*') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('masa.clinic.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                        </svg>
                                        Klinik Yönetimi
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="mt-auto">
                            <a href="{{ route('profile.edit') }}" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Profil
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}">
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <a href="{{ route('masa.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('masa.index') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('masa.index') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Kontrol Paneli
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('masa.vitrin.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('masa.vitrin.*') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('masa.vitrin.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                                Vitrin Yönetimi
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('masa.clinic.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('masa.clinic.*') ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50' }}">
                                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('masa.clinic.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                Klinik Yönetimi
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-auto">
                    <a href="{{ route('profile.edit') }}" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Profil
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div> 