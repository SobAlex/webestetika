<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WhyUs;

class WhyUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Основные блоки "Почему мы"
        WhyUs::create([
            'title' => 'Комплексный подход',
            'description' => 'Работаем над всеми аспектами продвижения: от технической оптимизации до контент-стратегии',
            'icon' => 'verified',
            'color' => '#10b981',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        WhyUs::create([
            'title' => 'Прозрачная отчетность',
            'description' => 'Предоставляем детальные отчеты о проделанной работе и достигнутых результатах',
            'icon' => 'analytics',
            'color' => '#3b82f6',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        WhyUs::create([
            'title' => 'Быстрые результаты',
            'description' => 'Первые улучшения позиций заметны уже через 2-4 недели после начала работ',
            'icon' => 'speed',
            'color' => '#f59e0b',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        WhyUs::create([
            'title' => 'Постоянная поддержка',
            'description' => 'Наши специалисты всегда на связи и готовы ответить на любые вопросы',
            'icon' => 'support',
            'color' => '#8b5cf6',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Дополнительные блоки через фабрику
        WhyUs::factory(2)->create();
    }
}
