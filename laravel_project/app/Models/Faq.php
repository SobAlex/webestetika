<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasPublishing;

use App\Contracts\PublishableInterface;

class Faq extends Model implements PublishableInterface
{
    use HasFactory, HasPublishing;
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_active',
        'show_on_homepage',
        'show_on_services'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'show_on_services' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope for FAQs visible on homepage.
     */
    public function scopeVisibleOnHomepage($query)
    {
        return $query->where('is_active', true)
                    ->where('show_on_homepage', true)
                    ->orderBy('sort_order');
    }

    // ниже не разобрано

    /**
     * Check if the FAQ is published.
     */
    public function isPublished(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the FAQ is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the FAQ should be visible on homepage.
     */
    public function isVisibleOnHomepage(): bool
    {
        return $this->is_active && $this->show_on_homepage;
    }

    /**
     * Check if the FAQ should be visible on services pages.
     */
    public function isVisibleOnServices(): bool
    {
        return $this->is_active && $this->show_on_services;
    }



    /**
     * Scope for FAQs visible on services pages.
     */
    public function scopeVisibleOnServices($query)
    {
        return $query->where('is_active', true)
                    ->where('show_on_services', true)
                    ->orderBy('sort_order');
    }
}
