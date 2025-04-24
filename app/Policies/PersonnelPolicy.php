<?php

namespace App\Policies;

use App\Models\Personnel;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonnelPolicy
{
    use HandlesAuthorization;

    public function viewAny(Personnel $user)
    {
        return $user->hasPermission('manage_personnel');
    }

    public function view(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') || $user->id === $personnel->id;
    }

    public function create(Personnel $user)
    {
        return $user->hasPermission('manage_personnel');
    }

    public function update(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') || $user->id === $personnel->id;
    }

    public function delete(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') && $user->id !== $personnel->id;
    }

    public function restore(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel');
    }

    public function forceDelete(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') && $user->id !== $personnel->id;
    }

    public function updatePermissions(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') && $user->id !== $personnel->id;
    }

    public function updateWorkingHours(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') || $user->id === $personnel->id;
    }

    public function updateOnboardingProgress(Personnel $user, Personnel $personnel)
    {
        return $user->hasPermission('manage_personnel') || $user->id === $personnel->id;
    }
} 