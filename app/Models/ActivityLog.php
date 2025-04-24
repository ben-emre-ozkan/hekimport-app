<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'related_model',
        'related_id',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function getRelatedModelAttribute($value)
    {
        if (!$value) return null;
        
        $model = "App\\Models\\" . $value;
        return class_exists($model) ? $model : null;
    }

    public function getRelatedModelInstance()
    {
        if (!$this->related_model || !$this->related_id) return null;
        
        return $this->related_model::find($this->related_id);
    }
} 