<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'clinic_id',
        'name',
        'email',
        'phone',
        'city',
        'address',
        'specialty',
        'bio',
        'profile_image',
        'is_active',
        'available_days',
        'available_times',
        'consultation_fee',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'available_days' => 'array',
        'available_times' => 'array',
        'consultation_fee' => 'decimal:2',
    ];
    
    /**
     * Get the user that owns the doctor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the clinic that owns the doctor.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
    
    /**
     * Get the appointments for the doctor.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    
    /**
     * Get all patients for the doctor.
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'appointments');
    }
}