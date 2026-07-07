<?php

namespace App\Contracts;

interface ImageableInterface
{
    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): ?string;

    /**
     * Get the image path.
     */
    public function getImageAttribute(): ?string;
}

