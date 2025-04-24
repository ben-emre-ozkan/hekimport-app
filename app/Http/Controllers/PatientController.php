<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function index(Request $request)
    {
        $query = Patient::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort functionality
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $patients = $query->paginate(10);

        return view('clinic.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('clinic.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:patients'],
            'phone' => ['required', 'string', 'max:20'],
            'id_number' => ['required', 'string', 'max:20', 'unique:patients'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', Rule::in(['male', 'female', 'other'])],
            'address' => ['required', 'string'],
            'medical_history' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:1024'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'insurance_provider' => ['nullable', 'string', 'max:255'],
            'insurance_number' => ['nullable', 'string', 'max:50'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('patients/photos', 'public');
        }

        $patient = Patient::create($validated);

        return redirect()->route('clinic.patients.show', $patient)
            ->with('success', 'Patient created successfully.');
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        
        $appointments = $patient->appointments()->latest()->paginate(5);
        $invoices = $patient->invoices()->latest()->paginate(5);
        $documents = $patient->documents()->latest()->paginate(5);
        
        return view('clinic.patients.show', compact('patient', 'appointments', 'invoices', 'documents'));
    }

    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        
        return view('clinic.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('patients')->ignore($patient->id)],
            'phone' => ['required', 'string', 'max:20'],
            'id_number' => ['required', 'string', 'max:20', Rule::unique('patients')->ignore($patient->id)],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', Rule::in(['male', 'female', 'other'])],
            'address' => ['required', 'string'],
            'medical_history' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:1024'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'insurance_provider' => ['nullable', 'string', 'max:255'],
            'insurance_number' => ['nullable', 'string', 'max:50'],
        ]);

        if ($request->hasFile('photo')) {
            if ($patient->photo) {
                Storage::delete($patient->photo);
            }
            $validated['photo'] = $request->file('photo')->store('patients/photos', 'public');
        }

        $patient->update($validated);

        return redirect()->route('clinic.patients.show', $patient)
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        
        if ($patient->photo) {
            Storage::delete($patient->photo);
        }
        
        $patient->delete();

        return redirect()->route('clinic.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    public function uploadDocument(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $request->validate([
            'document' => ['required', 'file', 'max:10240'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $path = $request->file('document')->store('patients/documents', 'public');
        
        $patient->documents()->create([
            'title' => $request->title,
            'description' => $request->description,
            'path' => $path,
            'type' => $request->file('document')->getMimeType(),
            'size' => $request->file('document')->getSize(),
        ]);

        return redirect()->route('clinic.patients.show', $patient)
            ->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument(Patient $patient, $documentId)
    {
        $this->authorize('update', $patient);
        
        $document = $patient->documents()->findOrFail($documentId);
        
        Storage::delete($document->path);
        $document->delete();

        return redirect()->route('clinic.patients.show', $patient)
            ->with('success', 'Document deleted successfully.');
    }

    public function addNote(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $request->validate([
            'note' => ['required', 'string'],
            'type' => ['required', 'string', Rule::in(['general', 'medical', 'follow-up'])],
        ]);

        $patient->notes()->create([
            'content' => $request->note,
            'type' => $request->type,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('clinic.patients.show', $patient)
            ->with('success', 'Note added successfully.');
    }
}