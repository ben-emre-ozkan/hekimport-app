<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitrinContent extends Model
{
    use HasFactory;

    public const TYPE_BLOG = 'blog';
    public const TYPE_ABOUT = 'about';
    public const TYPE_GALLERY = 'gallery';

    protected $fillable = [
        'vitrin_id',
        'type',
        'title',
        'content',
        'status',
    ];

    public function vitrin(): BelongsTo
    {
        return $this->belongsTo(Vitrin::class);
    }
} 