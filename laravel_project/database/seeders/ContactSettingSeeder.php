<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'email' => 'info@digital-agency.ru',
            'phone' => '+7 (495) 123-45-67',
            'address' => 'г. Москва, ул. Тверская, д. 15, офис 301',
            'working_hours' => 'Пн-Пт: 9:00-18:00, Сб: 10:00-16:00',
            'social_telegram' => 'https://t.me/digital_agency_ru',
            'social_whatsapp' => 'https://wa.me/74951234567',
            'social_vk' => 'https://vk.com/digital_agency_ru',
            'social_instagram' => 'https://instagram.com/digital_agency_ru',
            'is_active' => true,
        ];

        Contact::create($settings);
    }
}
