<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Hastalarım</h1>
            <div class="flex space-x-4">
                <form action="{{ route('doctor.patients.index') }}" method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Hasta adı ile ara..." class="rounded-lg border-gray-300">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Ara</button>
                </form>
            </div>
        </div>

        @if($patients->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">Henüz hasta bulunmamaktadır.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad Soyad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son Randevu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($patients as $patient)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $patient->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $patient->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($patient->last_appointment)
                                            {{ $patient->last_appointment->appointment_date->format('d.m.Y') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('doctor.patients.show', $patient) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detay</a>
                                    <a href="{{ route('doctor.patients.edit', $patient) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Düzenle</a>
                                    <a href="{{ route('doctor.appointments.create', ['patient_id' => $patient->id]) }}" class="text-green-600 hover:text-green-900">Randevu Oluştur</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</x-app-layout> 