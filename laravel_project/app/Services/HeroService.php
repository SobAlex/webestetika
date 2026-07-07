<?php

namespace App\Services;

use App\Models\HeroSection;
use Illuminate\Database\Eloquent\Collection;

class HeroService
{
    /**
     * Get all active hero sections.
     */
    public function getActiveHeroSections(): Collection
    {
        return HeroSection::active()->ordered()->get();
    }
}
