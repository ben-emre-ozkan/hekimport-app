<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $patient = Auth::user()->patient;
        return view('patient.profile.show', compact('patient'));
    }
    
    public function edit()
    {
        $patient = Auth::user()->patient;
        return view('patient.profile.edit', compact('patient'));
    }
    
    public function update(Request $request)
    {
        $patient = Auth::user()->patient;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,0+,0-',
            'allergies' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'current_medications' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Kullanıcı bilgilerini güncelle
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        // Hasta bilgilerini güncelle
        $patient->phone = $request->phone;
        $patient->address = $request->address;
        $patient->birth_date = $request->birth_date;
        $patient->gender = $request->gender;
        $patient->blood_type = $request->blood_type;
        $patient->allergies = $request->allergies;
        $patient->chronic_diseases = $request->chronic_diseases;
        $patient->current_medications = $request->current_medications;
        $patient->save();
        
        return redirect()->route('patient.profile.show')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('patient.profile.show')
            ->with('success', 'Şifreniz başarıyla güncellendi.');
    }
} 