<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;
        
        if (!$clinic) {
            return redirect()->route('login')->with('error', 'Klinik bilgileriniz bulunamadı.');
        }

        // Get today's appointments
        $todayAppointments = Appointment::where('clinic_id', $clinic->id)
            ->whereDate('appointment_date', Carbon::today())
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date')
            ->get();

        // Get upcoming appointments
        $upcomingAppointments = Appointment::where('clinic_id', $clinic->id)
            ->whereDate('appointment_date', '>', Carbon::today())
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        // Get clinic statistics
        $stats = [
            'total_doctors' => Doctor::where('clinic_id', $clinic->id)->count(),
            'total_services' => Service::where('clinic_id', $clinic->id)->count(),
            'today_appointments' => $todayAppointments->count(),
            'upcoming_appointments' => $upcomingAppointments->count(),
        ];

        return view('clinic.dashboard', compact('clinic', 'todayAppointments', 'upcomingAppointments', 'stats'));
    }
} 