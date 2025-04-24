<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Patients -->
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Toplam Hasta</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalPatients }}</dd>
    </div>

    <!-- Today's Appointments -->
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Bugünkü Randevular</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $todayAppointments }}</dd>
    </div>

    <!-- Monthly Revenue -->
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Aylık Gelir</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">₺{{ number_format($monthlyRevenue, 2) }}</dd>
    </div>

    <!-- Active Vitrin -->
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
        <dt class="truncate text-sm font-medium text-gray-500">Aktif Vitrin</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $hasActiveVitrin ? 'Evet' : 'Hayır' }}</dd>
    </div>
</div> 