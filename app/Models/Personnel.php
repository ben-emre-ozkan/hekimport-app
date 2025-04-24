<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Personnel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'address',
        'working_hours',
        'permissions',
        'last_login_at',
        'onboarding_completed',
        'onboarding_progress',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'working_hours' => 'array',
        'permissions' => 'array',
        'last_login_at' => 'datetime',
        'onboarding_completed' => 'boolean',
        'onboarding_progress' => 'array',
    ];

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getOnboardingProgressPercentage()
    {
        if (empty($this->onboarding_progress)) {
            return 0;
        }

        $totalSteps = count($this->onboarding_progress);
        $completedSteps = count(array_filter($this->onboarding_progress, fn($step) => $step['completed']));
        
        return ($completedSteps / $totalSteps) * 100;
    }
} 