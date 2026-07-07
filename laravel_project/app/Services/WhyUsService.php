<?php

namespace App\Services;

use App\Models\WhyUs;
use Illuminate\Database\Eloquent\Collection;

class WhyUsService
{
    /**
     * Get all active why us blocks.
     */
    public function getActiveWhyUsBlocks(): Collection
    {
        return WhyUs::active()->ordered()->get();
    }
}
