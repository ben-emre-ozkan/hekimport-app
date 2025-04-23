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
        'user_id',
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'address',
        'medical_history',
        'allergies',
        'is_active',
    ];
    
    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the user for the patient.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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