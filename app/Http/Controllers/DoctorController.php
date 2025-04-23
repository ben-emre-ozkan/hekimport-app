<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with(['clinic', 'user'])
            ->where('is_active', true)
            ->paginate(10);

        return view('doctor.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('doctor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:doctors',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $doctor = new Doctor($request->except('profile_image'));
        
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('doctors', 'public');
            $doctor->profile_image = $path;
        }
        
        $doctor->save();
        
        return redirect()->route('doctor.index')
            ->with('success', 'Doktor başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['clinic', 'user', 'appointments' => function ($query) {
            $query->where('status', 'scheduled')
                ->orderBy('appointment_date')
                ->take(5);
        }]);

        return view('doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return view('doctor.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:doctors,email,' . $doctor->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $doctor->fill($request->except('profile_image'));
        
        if ($request->hasFile('profile_image')) {
            if ($doctor->profile_image) {
                Storage::disk('public')->delete($doctor->profile_image);
            }
            
            $path = $request->file('profile_image')->store('doctors', 'public');
            $doctor->profile_image = $path;
        }
        
        $doctor->save();
        
        return redirect()->route('doctor.index')
            ->with('success', 'Doktor bilgileri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        if ($doctor->profile_image) {
            Storage::disk('public')->delete($doctor->profile_image);
        }
        
        $doctor->delete();
        
        return redirect()->route('doctor.index')
            ->with('success', 'Doktor başarıyla silindi.');
    }

    /**
     * Search doctors by name, specialty, or clinic.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $doctors = Doctor::with(['clinic', 'user'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('specialty', 'like', "%{$query}%")
                    ->orWhereHas('clinic', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    });
            })
            ->paginate(10);

        return view('doctor.index', compact('doctors', 'query'));
    }

    public function publicProfile(Doctor $doctor)
    {
        return view('doctor.public-profile', compact('doctor'));
    }
}