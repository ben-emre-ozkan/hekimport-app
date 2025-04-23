<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
    
    public function create()
    {
        return view('doctor.patients.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'password' => ['required', Password::defaults()],
        ]);
        
        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
        ]);
        
        // Create patient profile
        $patient = Patient::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'medical_history' => $request->medical_history,
            'allergies' => $request->allergies,
            'is_active' => true,
        ]);
        
        // Associate patient with doctor
        Auth::user()->doctor->patients()->attach($patient->id);
        
        return redirect()->route('doctor.patients.show', $patient)
            ->with('success', 'Patient created successfully.');
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
    
    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        return view('doctor.patients.edit', compact('patient'));
    }
    
    public function update(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
        ]);
        
        $patient->update($request->only([
            'name', 'phone', 'birth_date', 'gender', 'address',
            'medical_history', 'allergies'
        ]));
        
        return redirect()->route('doctor.patients.show', $patient)
            ->with('success', 'Patient information updated successfully.');
    }
} 