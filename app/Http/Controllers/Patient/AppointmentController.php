<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $patient = Auth::user()->patient;
        
        $status = $request->input('status');
        $date = $request->input('date');
        $search = $request->input('search');
        
        $query = $patient->appointments()->with('doctor');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($date) {
            $query->whereDate('appointment_date', $date);
        }
        
        if ($search) {
            $query->whereHas('doctor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('specialty', 'like', "%{$search}%");
            });
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);
        
        return view('patient.appointments.index', compact('appointments', 'status', 'date', 'search'));
    }
    
    public function show(Appointment $appointment)
    {
        // Yetkilendirme kontrolü
        $this->authorize('view', $appointment);
        
        return view('patient.appointments.show', compact('appointment'));
    }
    
    public function create()
    {
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();
        return view('patient.appointments.create', compact('doctors'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $patient = Auth::user()->patient;
        
        // Tarih ve zamanı birleştir
        $dateTime = $request->appointment_date . ' ' . $request->appointment_time;
        $appointmentDate = Carbon::createFromFormat('Y-m-d H:i', $dateTime);
        
        $appointment = new Appointment();
        $appointment->doctor_id = $request->doctor_id;
        $appointment->patient_id = $patient->id;
        $appointment->appointment_date = $appointmentDate;
        $appointment->status = 'scheduled';
        $appointment->notes = $request->notes;
        $appointment->save();
        
        return redirect()->route('patient.appointments.show', $appointment)
            ->with('success', 'Randevu başarıyla oluşturuldu.');
    }
    
    public function cancel(Appointment $appointment)
    {
        // Yetkilendirme kontrolü
        $this->authorize('cancel', $appointment);
        
        // Sadece planlanmış randevular iptal edilebilir
        if ($appointment->status !== 'scheduled') {
            return redirect()->back()
                ->with('error', 'Sadece planlanmış randevular iptal edilebilir.');
        }
        
        // Geçmiş randevular iptal edilemez
        if ($appointment->appointment_date < Carbon::now()) {
            return redirect()->back()
                ->with('error', 'Geçmiş randevular iptal edilemez.');
        }
        
        $appointment->status = 'cancelled';
        $appointment->save();
        
        return redirect()->back()
            ->with('success', 'Randevunuz başarıyla iptal edildi.');
    }
} 