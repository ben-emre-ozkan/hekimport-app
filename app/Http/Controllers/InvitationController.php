<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Notifications\PersonnelInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function accept(Request $request, $token)
    {
        $personnel = Personnel::where('invitation_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($personnel->invitation_expires_at->isPast()) {
            return redirect()->route('login')
                ->with('error', 'Invitation has expired. Please request a new one.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $personnel->update([
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'active',
            'invitation_token' => null,
            'invitation_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        auth()->login($personnel);

        return redirect()->route('clinic.dashboard')
            ->with('success', 'Welcome! Your account has been activated.');
    }

    public function resend(Personnel $personnel)
    {
        $this->authorize('update', $personnel);
        
        if ($personnel->status !== 'pending') {
            return back()->with('error', 'Can only resend invitation to pending personnel.');
        }

        // Generate new invitation token
        $token = Str::random(64);
        $expiresAt = now()->addDays(7);

        $personnel->update([
            'invitation_token' => $token,
            'invitation_expires_at' => $expiresAt,
        ]);

        // Send new invitation email
        $personnel->notify(new PersonnelInvitation($token));

        return back()->with('success', 'Invitation resent successfully.');
    }

    public function showAcceptForm($token)
    {
        $personnel = Personnel::where('invitation_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($personnel->invitation_expires_at->isPast()) {
            return redirect()->route('login')
                ->with('error', 'Invitation has expired. Please request a new one.');
        }

        return view('auth.accept-invitation', compact('personnel', 'token'));
    }
} 