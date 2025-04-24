<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'stripe_price_id',
        'price',
        'interval',
        'trial_days',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the clinics subscribed to this plan.
     */
    public function clinics(): HasMany
    {
        return $this->hasMany(Clinic::class);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' TL';
    }

    /**
     * Get the formatted interval.
     */
    public function getFormattedIntervalAttribute(): string
    {
        return match ($this->interval) {
            'month' => 'Monthly',
            'year' => 'Yearly',
            default => ucfirst($this->interval),
        };
    }

    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order plans by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
