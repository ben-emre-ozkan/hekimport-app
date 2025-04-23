<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;
        
        // Yaklaşan randevular
        $upcomingAppointments = $patient->appointments()
            ->where('status', 'scheduled')
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->orderBy('appointment_date')
            ->with('doctor')
            ->take(5)
            ->get();
        
        // Geçmiş randevular
        $pastAppointments = $patient->appointments()
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('appointment_date', 'desc')
            ->with('doctor')
            ->take(5)
            ->get();
        
        // Toplam randevu sayısı
        $appointmentsCount = $patient->appointments()->count();
        
        // Tamamlanan randevu sayısı
        $completedAppointmentsCount = $patient->appointments()
            ->where('status', 'completed')
            ->count();
        
        return view('patient.dashboard', compact(
            'upcomingAppointments',
            'pastAppointments',
            'appointmentsCount',
            'completedAppointmentsCount'
        ));
    }
} 