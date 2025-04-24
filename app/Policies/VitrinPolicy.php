<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vitrin;

class VitrinPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vitrin $vitrin): bool
    {
        return $vitrin->is_published || $user->id === $vitrin->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isDoctor() || $user->isStudent();
    }

    public function update(User $user, Vitrin $vitrin): bool
    {
        return $user->id === $vitrin->user_id;
    }

    public function delete(User $user, Vitrin $vitrin): bool
    {
        return $user->id === $vitrin->user_id;
    }
} 