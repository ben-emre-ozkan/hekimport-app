<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'email',
        'role',
        'token',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function accept()
    {
        $this->update(['status' => 'accepted']);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
} 