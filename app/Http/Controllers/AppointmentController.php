<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function index(Request $request)
    {
        $query = Appointment::query()->with(['patient', 'doctor']);

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('start_time', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);
        }

        // Filter by doctor
        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Get appointments for calendar view
        if ($request->has('view') && $request->view === 'calendar') {
            $appointments = $query->get()->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->name,
                    'start' => $appointment->start_time,
                    'end' => $appointment->end_time,
                    'status' => $appointment->status,
                    'color' => $this->getStatusColor($appointment->status),
                ];
            });

            return response()->json($appointments);
        }

        // Regular list view
        $appointments = $query->latest()->paginate(10);
        $doctors = Doctor::all();

        return view('clinic.appointments.index', compact('appointments', 'doctors'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        
        return view('clinic.appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'type' => ['required', 'string', Rule::in(['checkup', 'treatment', 'consultation', 'follow-up'])],
            'notes' => ['nullable', 'string'],
            'is_recurring' => ['boolean'],
            'recurring_pattern' => ['required_if:is_recurring,true', 'string', Rule::in(['daily', 'weekly', 'monthly'])],
            'recurring_end_date' => ['required_if:is_recurring,true', 'date', 'after:start_time'],
        ]);

        DB::transaction(function () use ($validated) {
            $appointment = Appointment::create([
                'patient_id' => $validated['patient_id'],
                'doctor_id' => $validated['doctor_id'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'type' => $validated['type'],
                'notes' => $validated['notes'],
                'status' => 'scheduled',
            ]);

            // Handle recurring appointments
            if ($validated['is_recurring']) {
                $this->createRecurringAppointments($appointment, $validated);
            }
        });

        return redirect()->route('clinic.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        return view('clinic.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $patients = Patient::all();
        $doctors = Doctor::all();
        
        return view('clinic.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'type' => ['required', 'string', Rule::in(['checkup', 'treatment', 'consultation', 'follow-up'])],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['scheduled', 'confirmed', 'cancelled', 'completed'])],
        ]);

        $appointment->update($validated);

        return redirect()->route('clinic.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $appointment->delete();

        return redirect()->route('clinic.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $request->validate([
            'status' => ['required', 'string', Rule::in(['scheduled', 'confirmed', 'cancelled', 'completed'])],
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->route('clinic.appointments.show', $appointment)
            ->with('success', 'Appointment status updated successfully.');
    }

    protected function createRecurringAppointments($appointment, $validated)
    {
        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['recurring_end_date']);
        $pattern = $validated['recurring_pattern'];

        while ($start->copy()->addDay() <= $end) {
            switch ($pattern) {
                case 'daily':
                    $start->addDay();
                    break;
                case 'weekly':
                    $start->addWeek();
                    break;
                case 'monthly':
                    $start->addMonth();
                    break;
            }

            if ($start <= $end) {
                Appointment::create([
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'start_time' => $start->copy(),
                    'end_time' => $start->copy()->addMinutes($appointment->start_time->diffInMinutes($appointment->end_time)),
                    'type' => $appointment->type,
                    'notes' => $appointment->notes,
                    'status' => 'scheduled',
                ]);
            }
        }
    }

    protected function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => '#3B82F6', // blue
            'confirmed' => '#10B981', // green
            'cancelled' => '#EF4444', // red
            'completed' => '#6B7280', // gray
            default => '#3B82F6',
        };
    }
}