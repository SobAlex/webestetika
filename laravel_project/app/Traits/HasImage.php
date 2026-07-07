<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasImage
{
    /**
     * Get the image URL attribute.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // Check if it's a legacy static image
        if (in_array($this->image, ['human.jpeg', 'human2.jpeg', 'human.webp'])) {
            return asset('images/' . $this->image);
        }

        // It's an uploaded image - check if it exists in public disk first
        $imagePath = str_starts_with($this->image, 'images/') ? $this->image : 'images/' . $this->image;

        if (Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        // If not in public, it might be in private disk (legacy Filament behavior)
        if (Storage::disk('local')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return null;
    }
}
