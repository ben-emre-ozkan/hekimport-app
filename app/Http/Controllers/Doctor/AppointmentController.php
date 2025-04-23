<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $status = $request->input('status');
        $date = $request->input('date');
        $search = $request->input('search');
        
        $query = $doctor->appointments()->with('patient');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($date) {
            $query->whereDate('appointment_date', $date);
        }
        
        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);
        
        return view('doctor.appointments.index', compact('appointments', 'status', 'date', 'search'));
    }
    
    public function show(Appointment $appointment)
    {
        // Yetkilendirme kontrolü
        $this->authorize('view', $appointment);
        
        return view('doctor.appointments.show', compact('appointment'));
    }
    
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Yetkilendirme kontrolü
        $this->authorize('update', $appointment);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $appointment->status = $request->status;
        $appointment->save();
        
        return redirect()->back()
            ->with('success', 'Randevu durumu başarıyla güncellendi.');
    }
    
    public function addNote(Request $request, Appointment $appointment)
    {
        // Yetkilendirme kontrolü
        $this->authorize('update', $appointment);
        
        $validator = Validator::make($request->all(), [
            'notes' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $appointment->notes = $request->notes;
        $appointment->save();
        
        return redirect()->back()
            ->with('success', 'Randevu notları başarıyla güncellendi.');
    }
} 