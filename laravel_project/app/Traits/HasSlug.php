<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $sourceField = $model->name ?? $model->title ?? null;
                if (!empty($sourceField)) {
                    $model->slug = Str::slug($sourceField);
                }
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $sourceField = $model->name ?? $model->title ?? null;
                if (!empty($sourceField)) {
                    $model->slug = Str::slug($sourceField);
                }
            }
        });
    }

    /**
     * Generate slug from name or title.
     */
    public function generateSlug()
    {
        $sourceField = $this->name ?? $this->title ?? null;
        if (!empty($sourceField)) {
            $this->slug = Str::slug($sourceField);
        }
    }
}
