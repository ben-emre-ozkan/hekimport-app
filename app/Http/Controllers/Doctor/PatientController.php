<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $search = $request->input('search');
        
        $query = $doctor->patients()->distinct();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $patients = $query->orderBy('name')->paginate(10);
        
        return view('doctor.patients.index', compact('patients', 'search'));
    }
    
    public function show(Patient $patient)
    {
        // Yetkilendirme kontrolü
        $this->authorize('view', $patient);
        
        $appointments = $patient->appointments()
            ->where('doctor_id', Auth::user()->doctor->id)
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        return view('doctor.patients.show', compact('patient', 'appointments'));
    }
} 