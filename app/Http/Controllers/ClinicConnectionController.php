<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClinicConnectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function connect(Request $request, Clinic $clinic)
    {
        $this->authorize('connect', $clinic);
        
        if ($clinic->hasMember(auth()->user())) {
            return back()->with('error', 'You are already a member of this clinic.');
        }

        DB::transaction(function () use ($clinic) {
            $clinic->members()->attach(auth()->id(), [
                'role' => 'doctor',
                'status' => 'pending',
                'joined_at' => now(),
            ]);

            // Notify clinic admins
            $clinic->members()
                ->wherePivot('role', 'admin')
                ->get()
                ->each(function ($admin) use ($clinic) {
                    $admin->notify(new \App\Notifications\NewClinicConnection($clinic, auth()->user()));
                });
        });

        return back()->with('success', 'Connection request sent successfully.');
    }

    public function disconnect(Clinic $clinic)
    {
        $this->authorize('disconnect', $clinic);
        
        if (!$clinic->hasMember(auth()->user())) {
            return back()->with('error', 'You are not a member of this clinic.');
        }

        DB::transaction(function () use ($clinic) {
            $clinic->members()->detach(auth()->id());

            // Notify clinic admins
            $clinic->members()
                ->wherePivot('role', 'admin')
                ->get()
                ->each(function ($admin) use ($clinic) {
                    $admin->notify(new \App\Notifications\ClinicDisconnection($clinic, auth()->user()));
                });
        });

        return back()->with('success', 'Disconnected from clinic successfully.');
    }

    public function approve(Clinic $clinic, User $user)
    {
        $this->authorize('manage', $clinic);
        
        if (!$clinic->hasMember($user)) {
            return back()->with('error', 'User is not a member of this clinic.');
        }

        $clinic->members()->updateExistingPivot($user->id, [
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Notify user
        $user->notify(new \App\Notifications\ClinicConnectionApproved($clinic));

        return back()->with('success', 'Connection request approved successfully.');
    }

    public function reject(Clinic $clinic, User $user)
    {
        $this->authorize('manage', $clinic);
        
        if (!$clinic->hasMember($user)) {
            return back()->with('error', 'User is not a member of this clinic.');
        }

        $clinic->members()->detach($user->id);

        // Notify user
        $user->notify(new \App\Notifications\ClinicConnectionRejected($clinic));

        return back()->with('success', 'Connection request rejected successfully.');
    }

    public function updateRole(Clinic $clinic, User $user, Request $request)
    {
        $this->authorize('manage', $clinic);
        
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['admin', 'doctor', 'staff'])],
        ]);

        if (!$clinic->hasMember($user)) {
            return back()->with('error', 'User is not a member of this clinic.');
        }

        $clinic->members()->updateExistingPivot($user->id, [
            'role' => $validated['role'],
        ]);

        // Notify user
        $user->notify(new \App\Notifications\ClinicRoleUpdated($clinic, $validated['role']));

        return back()->with('success', 'Member role updated successfully.');
    }

    public function suspend(Clinic $clinic, User $user)
    {
        $this->authorize('manage', $clinic);
        
        if (!$clinic->hasMember($user)) {
            return back()->with('error', 'User is not a member of this clinic.');
        }

        $clinic->members()->updateExistingPivot($user->id, [
            'status' => 'suspended',
        ]);

        // Notify user
        $user->notify(new \App\Notifications\ClinicMembershipSuspended($clinic));

        return back()->with('success', 'Member suspended successfully.');
    }

    public function reactivate(Clinic $clinic, User $user)
    {
        $this->authorize('manage', $clinic);
        
        if (!$clinic->hasMember($user)) {
            return back()->with('error', 'User is not a member of this clinic.');
        }

        $clinic->members()->updateExistingPivot($user->id, [
            'status' => 'active',
        ]);

        // Notify user
        $user->notify(new \App\Notifications\ClinicMembershipReactivated($clinic));

        return back()->with('success', 'Member reactivated successfully.');
    }
} 