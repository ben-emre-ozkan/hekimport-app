<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'medical_history',
        'notes',
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
        'medical_history' => 'array',
    ];
    
    /**
     * Get the user for the patient.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
    /**
     * Get the clinic for the patient.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
    
    /**
     * Get the appointments for the patient.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    
    /**
     * Get all doctors for the patient.
     */
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'appointments');
    }
}