<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Notifications\PersonnelInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function index(Request $request)
    {
        $query = Personnel::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Sort functionality
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $personnel = $query->paginate(10);

        return view('clinic.personnel.index', compact('personnel'));
    }

    public function create()
    {
        return view('clinic.personnel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:personnel'],
            'role' => ['required', 'string', Rule::in(['admin', 'doctor', 'nurse', 'receptionist', 'staff'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'working_hours' => ['nullable', 'array'],
            'permissions' => ['nullable', 'array'],
        ]);

        // Generate a random password
        $password = Str::random(10);
        $validated['password'] = Hash::make($password);
        $validated['status'] = 'pending';

        $personnel = Personnel::create($validated);

        // Send invitation email
        $personnel->notify(new PersonnelInvitation($password));

        return redirect()->route('clinic.personnel.index')
            ->with('success', 'Personnel invited successfully.');
    }

    public function show(Personnel $personnel)
    {
        $this->authorize('view', $personnel);
        
        $activities = $personnel->activities()->latest()->paginate(5);
        $tasks = $personnel->tasks()->latest()->paginate(5);
        
        return view('clinic.personnel.show', compact('personnel', 'activities', 'tasks'));
    }

    public function edit(Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        return view('clinic.personnel.edit', compact('personnel'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('personnel')->ignore($personnel->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'doctor', 'nurse', 'receptionist', 'staff'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive', 'suspended'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'working_hours' => ['nullable', 'array'],
            'permissions' => ['nullable', 'array'],
        ]);

        $personnel->update($validated);

        return redirect()->route('clinic.personnel.show', $personnel)
            ->with('success', 'Personnel updated successfully.');
    }

    public function destroy(Personnel $personnel)
    {
        $this->authorize('delete', $personnel);
        
        $personnel->delete();

        return redirect()->route('clinic.personnel.index')
            ->with('success', 'Personnel deleted successfully.');
    }

    public function resendInvitation(Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        if ($personnel->status !== 'pending') {
            return back()->with('error', 'Can only resend invitation to pending personnel.');
        }

        // Generate a new password
        $password = Str::random(10);
        $personnel->update(['password' => Hash::make($password)]);

        // Send new invitation email
        $personnel->notify(new PersonnelInvitation($password));

        return back()->with('success', 'Invitation resent successfully.');
    }

    public function updatePermissions(Request $request, Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', Rule::in([
                'view_patients',
                'edit_patients',
                'delete_patients',
                'view_appointments',
                'edit_appointments',
                'delete_appointments',
                'view_invoices',
                'edit_invoices',
                'delete_invoices',
                'view_reports',
                'edit_reports',
                'delete_reports',
                'manage_personnel',
                'manage_settings',
            ])],
        ]);

        $personnel->update(['permissions' => $validated['permissions']]);

        return back()->with('success', 'Permissions updated successfully.');
    }

    public function updateWorkingHours(Request $request, Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        $validated = $request->validate([
            'working_hours' => ['required', 'array'],
            'working_hours.*.day' => ['required', 'string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'working_hours.*.start' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'working_hours.*.end' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'working_hours.*.is_working' => ['required', 'boolean'],
        ]);

        $personnel->update(['working_hours' => $validated['working_hours']]);

        return back()->with('success', 'Working hours updated successfully.');
    }

    public function updateOnboardingProgress(Request $request, Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        $validated = $request->validate([
            'step' => ['required', 'string'],
            'completed' => ['required', 'boolean'],
        ]);

        $progress = $personnel->onboarding_progress ?? [];
        $progress[$validated['step']] = [
            'completed' => $validated['completed'],
            'completed_at' => now(),
        ];

        $personnel->update([
            'onboarding_progress' => $progress,
            'onboarding_completed' => count(array_filter($progress, fn($step) => $step['completed'])) === count($progress),
        ]);

        return back()->with('success', 'Onboarding progress updated successfully.');
    }
} 