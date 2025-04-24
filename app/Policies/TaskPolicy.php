<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Personnel;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(Personnel $user)
    {
        return true;
    }

    public function view(Personnel $user, Task $task)
    {
        return $user->hasPermission('manage_personnel') || 
               $user->id === $task->personnel_id || 
               $user->id === $task->assigned_by;
    }

    public function create(Personnel $user)
    {
        return $user->hasPermission('manage_personnel');
    }

    public function update(Personnel $user, Task $task)
    {
        return $user->hasPermission('manage_personnel') || 
               $user->id === $task->personnel_id || 
               $user->id === $task->assigned_by;
    }

    public function delete(Personnel $user, Task $task)
    {
        return $user->hasPermission('manage_personnel') || 
               $user->id === $task->assigned_by;
    }

    public function restore(Personnel $user, Task $task)
    {
        return $user->hasPermission('manage_personnel');
    }

    public function forceDelete(Personnel $user, Task $task)
    {
        return $user->hasPermission('manage_personnel');
    }

    public function complete(Personnel $user, Task $task)
    {
        return $user->id === $task->personnel_id;
    }
} 