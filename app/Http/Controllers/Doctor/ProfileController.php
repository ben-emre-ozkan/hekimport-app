<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        $doctor = Auth::user()->doctor;
        return view('doctor.profile.edit', compact('doctor'));
    }
    
    public function update(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'consultation_fee' => 'nullable|numeric',
            'available_days' => 'nullable|array',
            'available_times' => 'nullable|array',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->except('profile_image', 'available_days', 'available_times');
        
        // Profil fotoğrafını işle
        if ($request->hasFile('profile_image')) {
            if ($doctor->profile_image) {
                Storage::disk('public')->delete($doctor->profile_image);
            }
            
            $path = $request->file('profile_image')->store('doctors', 'public');
            $data['profile_image'] = $path;
        }
        
        // Müsait günleri ve saatleri kaydet
        if ($request->has('available_days')) {
            $data['available_days'] = json_encode($request->available_days);
        }
        
        if ($request->has('available_times')) {
            $data['available_times'] = json_encode($request->available_times);
        }
        
        $doctor->update($data);
        
        // Kullanıcı adını güncelle
        Auth::user()->update([
            'name' => $request->name,
        ]);
        
        return redirect()->route('doctor.profile.edit')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }
} 