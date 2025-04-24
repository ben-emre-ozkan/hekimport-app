<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentVitrin extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'template',
        'title',
        'tagline',
        'about',
        'profile_photo',
        'cover_photo',
        'contact_info',
        'achievements',
        'publications',
        'certifications',
        'custom_sections',
        'seo_metadata',
        'theme_settings',
    ];

    protected $casts = [
        'contact_info' => 'array',
        'achievements' => 'array',
        'publications' => 'array',
        'certifications' => 'array',
        'custom_sections' => 'array',
        'seo_metadata' => 'array',
        'theme_settings' => 'array',
    ];

    /**
     * Get the student that owns the vitrin.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the formatted contact information.
     */
    public function getFormattedContactInfo(): array
    {
        return array_filter($this->contact_info ?? []);
    }

    /**
     * Get the SEO title.
     */
    public function getSeoTitle(): string
    {
        return $this->seo_metadata['title'] ?? $this->title;
    }

    /**
     * Get the SEO description.
     */
    public function getSeoDescription(): string
    {
        return $this->seo_metadata['description'] ?? substr(strip_tags($this->about), 0, 160);
    }

    /**
     * Get the theme color scheme.
     */
    public function getColorScheme(): array
    {
        return $this->theme_settings['colors'] ?? [
            'primary' => '#4F46E5',
            'secondary' => '#7C3AED',
            'accent' => '#EC4899',
        ];
    }
} 