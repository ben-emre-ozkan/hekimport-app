<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'university',
        'graduation_year',
        'student_id',
        'bio',
        'interests',
        'skills',
        'social_links',
        'privacy_settings',
        'subdomain',
        'status',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'interests' => 'array',
        'skills' => 'array',
        'social_links' => 'array',
        'privacy_settings' => 'array',
    ];

    /**
     * Get the user that owns the student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student's vitrin.
     */
    public function vitrin(): HasOne
    {
        return $this->hasOne(StudentVitrin::class);
    }

    /**
     * Get the student's education entries.
     */
    public function education(): HasMany
    {
        return $this->hasMany(StudentEducation::class);
    }

    /**
     * Get the student's experience entries.
     */
    public function experience(): HasMany
    {
        return $this->hasMany(StudentExperience::class);
    }

    /**
     * Get the student's case gallery entries.
     */
    public function cases(): HasMany
    {
        return $this->hasMany(StudentCase::class);
    }

    /**
     * Get the student's mentor requests.
     */
    public function mentorRequests(): HasMany
    {
        return $this->hasMany(MentorRequest::class);
    }

    /**
     * Get the student's academic resources.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(AcademicResource::class);
    }

    /**
     * Check if the student's profile is public.
     */
    public function isPublic(): bool
    {
        return $this->privacy_settings['profile_visibility'] ?? false;
    }

    /**
     * Get the student's full subdomain URL.
     */
    public function getSubdomainUrl(): string
    {
        return "https://{$this->subdomain}." . config('app.akademi_domain');
    }

    /**
     * Scope a query to only include verified students.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope a query to only include public profiles.
     */
    public function scopePublic($query)
    {
        return $query->whereJsonContains('privacy_settings->profile_visibility', true);
    }
} 