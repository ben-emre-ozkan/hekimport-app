<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info('Registration attempt', $request->all());

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:clinic,patient'],
        ];

        // Add role-specific validation rules
        if ($request->role === 'clinic') {
            $rules['phone'] = ['required', 'string', 'regex:/^[0-9]{10}$/'];
            $rules['city'] = ['required', 'string', 'max:255'];
        } else {
            $rules['phone_number'] = ['required', 'string', 'regex:/^[0-9]{10}$/'];
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            Log::info('Creating user', ['email' => $validated['email']]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            Log::info('User created', ['user_id' => $user->id]);

            if ($validated['role'] === 'clinic') {
                Log::info('Creating clinic profile', ['user_id' => $user->id]);

                // Create clinic profile
                $clinic = Clinic::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'city' => $validated['city'],
                    'is_active' => true,
                ]);

                Log::info('Clinic created', ['clinic_id' => $clinic->id]);

                // Create first doctor profile for the clinic
                $doctor = Doctor::create([
                    'user_id' => $user->id,
                    'clinic_id' => $clinic->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'is_active' => true,
                ]);

                Log::info('Doctor created', ['doctor_id' => $doctor->id]);
            } else {
                Log::info('Creating patient profile', ['user_id' => $user->id]);

                // Create patient profile
                $patient = Patient::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone_number'],
                    'is_active' => true,
                ]);

                Log::info('Patient created', ['patient_id' => $patient->id]);
            }

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            // Redirect based on role
            if ($validated['role'] === 'clinic') {
                return redirect()->route('clinic.dashboard');
            } else {
                return redirect()->route('patient.dashboard');
            }
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Kayıt işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }
}
