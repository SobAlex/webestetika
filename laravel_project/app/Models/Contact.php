<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'address',
        'working_hours',
        'social_telegram',
        'social_whatsapp',
        'social_vk',
        'social_instagram',
        'social_youtube',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the single contact instance (singleton pattern).
     */
    public static function getInstance()
    {
        // Сначала попробуем найти активную запись с данными
        $contact = static::where('is_active', true)
            ->where(function($query) {
                $query->where('email', '!=', '')
                      ->orWhere('phone', '!=', '')
                      ->orWhere('address', '!=', '');
            })
            ->first();

        // Если не найдена, попробуем любую активную запись
        if (!$contact) {
            $contact = static::where('is_active', true)->first();
        }

        // Если все еще не найдена, создаем новую
        if (!$contact) {
            $contact = static::create([
                'email' => '',
                'phone' => '',
                'address' => '',
                'working_hours' => '',
                'social_telegram' => '',
                'social_whatsapp' => '',
                'social_vk' => '',
                'social_instagram' => '',
                'social_youtube' => '',
                'is_active' => true,
            ]);
        }

        return $contact;
    }

    /**
     * Get contact data for frontend.
     */
    public static function getContactData(): array
    {
        $contact = static::getInstance();

        return [
            'email' => $contact->email,
            'phone' => $contact->phone,
            'address' => $contact->address,
            'working_hours' => $contact->working_hours,
            'social' => [
                'telegram' => $contact->social_telegram,
                'whatsapp' => $contact->social_whatsapp,
                'vk' => $contact->social_vk,
                'instagram' => $contact->social_instagram,
                'youtube' => $contact->social_youtube,
            ],
        ];
    }

    /**
     * Check if the contact is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope for active contacts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
