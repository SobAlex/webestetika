<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class ServiceService
{
    /**
     * Get all featured services.
     */
    public function getFeaturedServices(): Collection
    {
        return Service::published()->showOnHomepage()->ordered()->take(6)->get();
    }
}
