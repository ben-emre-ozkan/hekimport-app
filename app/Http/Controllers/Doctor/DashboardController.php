<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        
        // Toplam randevu sayısı
        $appointmentsCount = $doctor->appointments()->count();
        
        // Bugünkü randevular
        $todayAppointments = $doctor->appointments()
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_date')
            ->with('patient')
            ->get();
        
        $todayAppointmentsCount = $todayAppointments->count();
        
        // Yaklaşan randevular (gelecek 7 gün)
        $upcomingAppointments = $doctor->appointments()
            ->where('status', 'scheduled')
            ->whereDate('appointment_date', '>', Carbon::today())
            ->whereDate('appointment_date', '<=', Carbon::today()->addDays(7))
            ->orderBy('appointment_date')
            ->with('patient')
            ->take(5)
            ->get();
        
        // Toplam hasta sayısı
        $patientsCount = $doctor->patients()->distinct()->count();
        
        return view('doctor.dashboard', compact(
            'appointmentsCount',
            'todayAppointments',
            'todayAppointmentsCount',
            'upcomingAppointments',
            'patientsCount'
        ));
    }
} 