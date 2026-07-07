<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeroSection;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Основной Hero блок
        HeroSection::create([
            'title' => 'Профессиональное SEO продвижение сайтов',
            'description' => 'Увеличиваем органический трафик и конверсию вашего сайта с помощью современных SEO-стратегий. Работаем с Google и Яндекс.',
            'image' => null, // Заполните путь к изображению при необходимости
            'button_text' => 'Получить консультацию',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        // Дополнительные Hero блоки
        HeroSection::create([
            'title' => 'Комплексный интернет-маркетинг',
            'description' => 'Полный спектр услуг по продвижению в интернете: от создания сайта до настройки рекламных кампаний.',
            'image' => null,
            'button_text' => 'Узнать больше',
            'is_active' => false,
            'sort_order' => 1,
        ]);

        HeroSection::create([
            'title' => 'Аудит и оптимизация сайта',
            'description' => 'Проведем детальный анализ вашего сайта и предложим конкретные рекомендации по улучшению позиций в поиске.',
            'image' => null,
            'button_text' => 'Заказать аудит',
            'is_active' => false,
            'sort_order' => 2,
        ]);
    }
}
