<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vitrin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $clinic = $user->clinic()->first();

        $stats = [
            'totalPatients' => $user->patients()->count(),
            'todayAppointments' => $user->appointments()
                ->whereDate('appointment_date', today())
                ->count(),
            'monthlyRevenue' => $user->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->sum('amount'),
            'hasActiveVitrin' => $user->vitrin()->exists(),
        ];

        $recentAppointments = $user->appointments()
            ->with('patient')
            ->latest()
            ->take(5)
            ->get();

        $recentPatients = $user->patients()
            ->latest()
            ->take(5)
            ->get();

        return view('masa.index', compact('stats', 'recentAppointments', 'recentPatients', 'clinic'));
    }
} 