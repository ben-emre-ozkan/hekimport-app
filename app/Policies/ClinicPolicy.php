<?php

namespace App\Policies;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClinicPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Clinic $clinic)
    {
        return $clinic->isPublic() || $clinic->hasMember($user);
    }

    public function create(User $user)
    {
        return $user->hasRole('doctor');
    }

    public function update(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && $clinic->getMemberRole($user) === 'admin';
    }

    public function delete(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && $clinic->getMemberRole($user) === 'admin';
    }

    public function restore(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && $clinic->getMemberRole($user) === 'admin';
    }

    public function forceDelete(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && $clinic->getMemberRole($user) === 'admin';
    }

    public function connect(User $user, Clinic $clinic)
    {
        return $user->hasRole('doctor') && !$clinic->hasMember($user);
    }

    public function disconnect(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user);
    }

    public function manage(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && $clinic->getMemberRole($user) === 'admin';
    }

    public function shareResources(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && in_array($clinic->getMemberRole($user), ['admin', 'doctor']);
    }

    public function viewResources(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user);
    }

    public function updateSettings(User $user, Clinic $clinic)
    {
        return $clinic->hasMember($user) && $clinic->getMemberRole($user) === 'admin';
    }
} 