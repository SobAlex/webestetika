<?php

namespace App\Models;

use App\Contracts\PublishableInterface;
use App\Traits\HasPublishing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model implements PublishableInterface
{
    use HasFactory, HasPublishing;
    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Check if the setting is published.
     */
    public function isPublished(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the setting is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->active()->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key.
     */
    public static function setValue(string $key, $value): bool
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        ) !== null;
    }

    /**
     * Get all contact settings as array.
     */
    public static function getAllSettings(): array
    {
        return static::active()
            ->ordered()
            ->pluck('value', 'key')
            ->toArray();
    }
}
