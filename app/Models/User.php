<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_DOCTOR = 'doctor';
    public const ROLE_PERSONEL = 'personel';
    public const ROLE_STUDENT = 'student';
    public const ROLE_CLINIC = 'clinic';
    public const ROLE_PATIENT = 'patient';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'assigned_doctor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the doctor profile associated with the user.
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the patient profile associated with the user.
     */
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get the clinic associated with the user.
     */
    public function clinic()
    {
        return $this->belongsToMany(Clinic::class, 'doctor_clinic')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function assignedDoctor()
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    public function assignedPersonel()
    {
        return $this->hasMany(User::class, 'assigned_doctor_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function vitrin()
    {
        return $this->hasOne(Vitrin::class);
    }

    /**
     * Check if user is a doctor
     */
    public function isDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    /**
     * Check if user is a personel
     */
    public function isPersonel(): bool
    {
        return $this->role === self::ROLE_PERSONEL;
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Check if user is a clinic
     */
    public function isClinic(): bool
    {
        return $this->role === self::ROLE_CLINIC;
    }

    /**
     * Check if user is a patient
     */
    public function isPatient(): bool
    {
        return $this->role === self::ROLE_PATIENT;
    }

    public function hasClinic(): bool
    {
        return $this->clinic()->exists();
    }

    public function canManageVitrin(): bool
    {
        return $this->isDoctor() || $this->isStudent() || $this->isClinic();
    }

    public function canManageClinic(): bool
    {
        return $this->isDoctor() || $this->isClinic();
    }

    public function canManagePatients(): bool
    {
        return $this->isDoctor() || $this->isPersonel() || $this->isClinic();
    }

    public function canManageAppointments(): bool
    {
        return $this->isDoctor() || $this->isPersonel() || $this->isClinic();
    }

    public function canViewAcademy(): bool
    {
        return $this->isStudent();
    }
}
