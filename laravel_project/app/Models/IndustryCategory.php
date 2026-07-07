<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasPublishing;

use App\Contracts\PublishableInterface;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndustryCategory extends Model implements PublishableInterface
{
    use HasFactory, HasPublishing, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    // ниже пока не разобраны

    /**
     * Check if the category is published.
     */
    public function isPublished(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the category is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Связь с кейсами
     */
    public function cases(): HasMany
    {
        return $this->hasMany(ProjectCase::class, 'industry_category_id');
    }

    /**
     * Scope a query to only include inactive categories.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get cases with only active categories.
     */
    public function activeCases(): HasMany
    {
        return $this->cases()->whereHas('industryCategory', function($query) {
            $query->where('is_active', true);
        });
    }


}
