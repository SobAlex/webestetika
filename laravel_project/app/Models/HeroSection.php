<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope для получения только активных Hero блоков
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope для сортировки по порядку
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // методы ниже пока не разобраны. Разобраться, где применяются. Не нужные удалить.

    /**
     * Проверяет, активен ли Hero блок
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Получает URL изображения
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Check if the hero section is published.
     */
    public function isPublished(): bool
    {
        return $this->is_active;
    }
}
