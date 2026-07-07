<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Get image URL for any model.
     */
    public static function getImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }

        // Check if it's a legacy static image
        if (in_array($image, ['human.jpeg', 'human2.jpeg', 'human.webp'])) {
            return asset('images/' . $image);
        }

        // It's an uploaded image - check if it exists in public disk first
        $imagePath = str_starts_with($image, 'images/') ? $image : 'images/' . $image;

        if (Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        // If not in public, it might be in private disk (legacy Filament behavior)
        if (Storage::disk('local')->exists($image)) {
            return asset('storage/' . $image);
        }

        return null;
    }

    /**
     * Store uploaded image.
     */
    public static function storeImage($file, string $directory = 'images'): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Delete image file.
     */
    public static function deleteImage(string $imagePath): bool
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }

        if (Storage::disk('local')->exists($imagePath)) {
            return Storage::disk('local')->delete($imagePath);
        }

        return false;
    }
}


