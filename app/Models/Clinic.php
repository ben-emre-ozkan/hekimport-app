<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clinic extends Model
{
    use HasFactory, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'logo',
        'banner',
        'specialties',
        'working_hours',
        'settings',
        'status',
        'visibility',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specialties' => 'array',
        'working_hours' => 'array',
        'settings' => 'array',
    ];

    /**
     * Get the user that owns the clinic.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the users associated with the clinic.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'doctor_clinic')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the members for the clinic.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'clinic_members')
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get the doctors for the clinic.
     */
    public function doctors()
    {
        return $this->belongsToMany(User::class, 'clinic_members')
            ->wherePivot('role', 'doctor')
            ->withPivot('status', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get the staff for the clinic.
     */
    public function staff()
    {
        return $this->belongsToMany(User::class, 'clinic_members')
            ->wherePivot('role', 'staff')
            ->withPivot('status', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get the invitations for the clinic.
     */
    public function invitations()
    {
        return $this->hasMany(ClinicInvitation::class);
    }

    /**
     * Get the shared resources for the clinic.
     */
    public function sharedResources()
    {
        return $this->hasMany(SharedResource::class);
    }

    /**
     * Check if the clinic is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the clinic is public.
     */
    public function isPublic()
    {
        return $this->visibility === 'public';
    }

    /**
     * Check if the clinic has a member.
     */
    public function hasMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if the clinic has a doctor.
     */
    public function hasDoctor(User $user)
    {
        return $this->doctors()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if the clinic has a staff member.
     */
    public function hasStaff(User $user)
    {
        return $this->staff()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the member role.
     */
    public function getMemberRole(User $user)
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->role : null;
    }

    /**
     * Get the member status.
     */
    public function getMemberStatus(User $user)
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->status : null;
    }

    /**
     * Get the plan that the clinic is subscribed to.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if the clinic is on a trial.
     */
    public function isOnTrial(): bool
    {
        return $this->subscription('default')?->onTrial() ?? false;
    }

    /**
     * Check if the clinic is subscribed.
     */
    public function isSubscribed(): bool
    {
        return $this->subscription('default')?->active() ?? false;
    }

    /**
     * Check if the clinic is on grace period.
     */
    public function isOnGracePeriod(): bool
    {
        return $this->subscription('default')?->onGracePeriod() ?? false;
    }

    /**
     * Get the subscription status.
     */
    public function getSubscriptionStatus(): string
    {
        if ($this->isOnTrial()) {
            return 'trial';
        }

        if ($this->isSubscribed()) {
            return 'active';
        }

        if ($this->isOnGracePeriod()) {
            return 'grace_period';
        }

        return 'inactive';
    }

    /**
     * Get the formatted trial end date.
     */
    public function getTrialEndDate(): ?string
    {
        $subscription = $this->subscription('default');
        
        if ($subscription && $subscription->onTrial()) {
            return $subscription->trial_ends_at->format('F j, Y');
        }

        return null;
    }
} 