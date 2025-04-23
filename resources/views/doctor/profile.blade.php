<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/3">
                            <div class="bg-gray-100 rounded-lg p-6">
                                <div class="text-center mb-6">
                                    <div class="w-32 h-32 mx-auto bg-gray-300 rounded-full mb-4">
                                        @if($doctor->profile_photo_path)
                                            <img src="{{ Storage::url($doctor->profile_photo_path) }}" alt="{{ $doctor->name }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $doctor->name }}</h2>
                                    <p class="text-gray-600">{{ $doctor->specialty }}</p>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Şehir</h3>
                                        <p class="mt-1 text-gray-900">{{ $doctor->city }}</p>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">İletişim</h3>
                                        <p class="mt-1 text-gray-900">{{ $doctor->email }}</p>
                                        @if($doctor->phone)
                                            <p class="mt-1 text-gray-900">{{ $doctor->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:w-2/3">
                            <div class="prose max-w-none">
                                <h3 class="text-xl font-semibold mb-4">Hakkında</h3>
                                <div class="text-gray-600">
                                    {!! nl2br(e($doctor->about)) !!}
                                </div>

                                @if($doctor->education)
                                    <h3 class="text-xl font-semibold mt-8 mb-4">Eğitim</h3>
                                    <div class="text-gray-600">
                                        {!! nl2br(e($doctor->education)) !!}
                                    </div>
                                @endif

                                @if($doctor->experience)
                                    <h3 class="text-xl font-semibold mt-8 mb-4">Deneyim</h3>
                                    <div class="text-gray-600">
                                        {!! nl2br(e($doctor->experience)) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 