<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ClinicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Clinic::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        // Filter by specialty
        if ($request->has('specialty')) {
            $query->whereJsonContains('specialties', $request->specialty);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by visibility
        if ($request->has('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        // Sort functionality
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $clinics = $query->paginate(10);

        return view('clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('clinics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'website' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'banner' => ['nullable', 'image', 'max:2048'],
            'specialties' => ['nullable', 'array'],
            'working_hours' => ['nullable', 'array'],
            'settings' => ['nullable', 'array'],
            'visibility' => ['required', 'string', Rule::in(['public', 'private'])],
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clinics/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('clinics/banners', 'public');
        }

        $clinic = Clinic::create($validated);

        // Add creator as admin
        $clinic->members()->attach(auth()->id(), [
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return redirect()->route('clinics.show', $clinic)
            ->with('success', 'Clinic created successfully.');
    }

    public function show(Clinic $clinic)
    {
        $this->authorize('view', $clinic);
        
        $members = $clinic->members()->with('profile')->get();
        $doctors = $clinic->doctors()->with('profile')->get();
        $staff = $clinic->staff()->with('profile')->get();
        $sharedResources = $clinic->sharedResources()->latest()->paginate(5);
        
        return view('clinics.show', compact('clinic', 'members', 'doctors', 'staff', 'sharedResources'));
    }

    public function edit(Clinic $clinic)
    {
        $this->authorize('update', $clinic);
        
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $this->authorize('update', $clinic);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'website' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'banner' => ['nullable', 'image', 'max:2048'],
            'specialties' => ['nullable', 'array'],
            'working_hours' => ['nullable', 'array'],
            'settings' => ['nullable', 'array'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive', 'suspended'])],
            'visibility' => ['required', 'string', Rule::in(['public', 'private'])],
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            if ($clinic->logo) {
                Storage::delete($clinic->logo);
            }
            $validated['logo'] = $request->file('logo')->store('clinics/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($clinic->banner) {
                Storage::delete($clinic->banner);
            }
            $validated['banner'] = $request->file('banner')->store('clinics/banners', 'public');
        }

        $clinic->update($validated);

        return redirect()->route('clinics.show', $clinic)
            ->with('success', 'Clinic updated successfully.');
    }

    public function destroy(Clinic $clinic)
    {
        $this->authorize('delete', $clinic);
        
        // Delete associated files
        if ($clinic->logo) {
            Storage::delete($clinic->logo);
        }
        if ($clinic->banner) {
            Storage::delete($clinic->banner);
        }

        $clinic->delete();

        return redirect()->route('clinics.index')
            ->with('success', 'Clinic deleted successfully.');
    }

    public function dashboard(Clinic $clinic)
    {
        $this->authorize('view', $clinic);
        
        $stats = [
            'total_members' => $clinic->members()->count(),
            'total_doctors' => $clinic->doctors()->count(),
            'total_staff' => $clinic->staff()->count(),
            'total_resources' => $clinic->sharedResources()->count(),
            'recent_activities' => $clinic->activities()->latest()->take(5)->get(),
        ];
        
        return view('clinics.dashboard', compact('clinic', 'stats'));
    }

    public function settings(Clinic $clinic)
    {
        $this->authorize('update', $clinic);
        
        return view('clinics.settings', compact('clinic'));
    }

    public function updateSettings(Request $request, Clinic $clinic)
    {
        $this->authorize('update', $clinic);
        
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.branding' => ['nullable', 'array'],
            'settings.notifications' => ['nullable', 'array'],
            'settings.privacy' => ['nullable', 'array'],
            'settings.sharing' => ['nullable', 'array'],
        ]);

        $clinic->update(['settings' => $validated['settings']]);

        return back()->with('success', 'Clinic settings updated successfully.');
    }
} 