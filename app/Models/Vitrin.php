<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vitrin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'subdomain',
        'theme',
        'meta_title',
        'meta_description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(VitrinContent::class);
    }
} 