<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Randevularım</h1>
            <div class="flex space-x-4">
                <form action="{{ route('doctor.appointments.index') }}" method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Hasta adı ile ara..." class="rounded-lg border-gray-300">
                    <select name="status" class="rounded-lg border-gray-300">
                        <option value="">Tüm Durumlar</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Bekleyen</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Onaylanmış</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal Edilmiş</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlanmış</option>
                    </select>
                    <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-gray-300">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Filtrele</button>
                </form>
            </div>
        </div>

        @if($appointments->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">Henüz randevu bulunmamaktadır.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('d.m.Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->appointment_time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $appointment->status == 'pending' ? 'Bekleyen' :
                                           ($appointment->status == 'confirmed' ? 'Onaylanmış' :
                                           ($appointment->status == 'cancelled' ? 'İptal Edilmiş' : 'Tamamlanmış')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detay</a>
                                    @if($appointment->status == 'pending')
                                        <form action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Onayla</button>
                                        </form>
                                        <form action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="text-red-600 hover:text-red-900">İptal Et</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</x-app-layout> 