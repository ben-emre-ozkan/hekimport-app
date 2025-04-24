<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'assigned_by',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'notes' => 'array',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function assigner()
    {
        return $this->belongsTo(Personnel::class, 'assigned_by');
    }

    public function isOverdue()
    {
        return $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function getPriorityColor()
    {
        return match($this->priority) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray',
        };
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
} 