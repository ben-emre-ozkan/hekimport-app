<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoSettings extends Model
{
    protected $fillable = [
        'vitrin_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_markup',
        'index_follow',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'index_follow' => 'boolean',
    ];

    public function vitrin(): BelongsTo
    {
        return $this->belongsTo(Vitrin::class);
    }
}
