<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'patient'])->orderBy('appointment_date')->get();
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::where('is_active', true)->get();
        $patients = Patient::all();
        return view('appointments.create', compact('doctors', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Appointment::create($request->all());
        
        return redirect()->route('appointments.index')
            ->with('success', 'Randevu başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::where('is_active', true)->get();
        $patients = Patient::all();
        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appointment->update($request->all());
        
        return redirect()->route('appointments.index')
            ->with('success', 'Randevu bilgileri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Randevu başarıyla silindi.');
    }
    
    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $appointment->status = $request->status;
        $appointment->save();
        
        return response()->json(['success' => true]);
    }

    public function book(Request $request, Doctor $doctor)
    {
        $validator = Validator::make($request->all(), [
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tarih ve zamanı birleştir
        $dateTime = $request->appointment_date . ' ' . $request->appointment_time;
        $appointmentDate = Carbon::createFromFormat('Y-m-d H:i', $dateTime);

        // Hasta kontrol - Giriş yapmış mı?
        $patient = null;
        if (Auth::check() && Auth::user()->isPatient()) {
            $patient = Auth::user()->patient;
        } else {
            // Misafir randevu - önce hasta kaydı yapalım
            $patient = Patient::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                ]
            );
        }

        // Randevu oluştur
        $appointment = new Appointment();
        $appointment->doctor_id = $doctor->id;
        $appointment->patient_id = $patient->id;
        $appointment->appointment_date = $appointmentDate;
        $appointment->status = 'scheduled';
        $appointment->notes = $request->notes;
        $appointment->save();

        return redirect()->route('appointments.confirmation', $appointment)
            ->with('success', 'Randevu başarıyla oluşturuldu.');
    }

    public function confirmation(Appointment $appointment)
    {
        return view('appointments.confirmation', compact('appointment'));
    }
}