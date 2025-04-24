<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('DoctorDashboardController@index called', [
            'user' => Auth::user(),
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'is_doctor' => Auth::user()->isDoctor()
        ]);

        $doctor = Auth::user()->doctor;
        
        if (!$doctor) {
            Log::error('Doctor profile not found', [
                'user_id' => Auth::id()
            ]);
            abort(404, 'Doctor profile not found');
        }

        Log::info('Doctor profile found', [
            'doctor_id' => $doctor->id
        ]);
        
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
        
        Log::info('Dashboard data prepared', [
            'appointments_count' => $appointmentsCount,
            'today_appointments_count' => $todayAppointmentsCount,
            'upcoming_appointments_count' => $upcomingAppointments->count(),
            'patients_count' => $patientsCount
        ]);
        
        return view('doctor.dashboard', compact(
            'appointmentsCount',
            'todayAppointments',
            'todayAppointmentsCount',
            'upcomingAppointments',
            'patientsCount'
        ));
    }
} 