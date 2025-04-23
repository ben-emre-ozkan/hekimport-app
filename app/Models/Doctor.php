<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'specialty',
        'email',
        'phone',
        'bio',
        'profile_image',
        'is_active',
    ];
    
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