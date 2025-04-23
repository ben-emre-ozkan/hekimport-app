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

    /**
     * Book a new appointment with a doctor.
     */
    public function book(Request $request, Doctor $doctor)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment = new Appointment([
            'doctor_id' => $doctor->id,
            'patient_id' => Auth::user()->patient->id,
            'appointment_date' => $request->appointment_date . ' ' . $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        $appointment->save();

        return redirect()->route('appointments.confirm', $appointment)
            ->with('success', 'Randevunuz başarıyla oluşturuldu. Lütfen onaylayın.');
    }

    /**
     * Confirm an appointment.
     */
    public function confirm(Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::user()->patient->id) {
            abort(403);
        }

        $appointment->update(['status' => 'scheduled']);

        return redirect()->route('patient.appointments')
            ->with('success', 'Randevunuz onaylandı.');
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::user()->patient->id) {
            abort(403);
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('patient.appointments')
            ->with('success', 'Randevunuz iptal edildi.');
    }
}