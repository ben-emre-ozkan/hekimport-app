<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'clinic_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'type',
        'reason',
    ];
    
    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
    /**
     * Get the clinic that owns the appointment.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
    
    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
    /**
     * Get the patient that owns the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}