<?php

namespace Database\Seeders;

use App\Models\IndustryCategory;
use Illuminate\Database\Seeder;

class IndustryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем категории отраслей для кейсов
        $categories = [
            [
                'name' => 'E-commerce',
                'slug' => 'ecommerce',
                'description' => 'Кейсы продвижения интернет-магазинов и e-commerce проектов',
                'icon' => 'shopping_cart',
                'color' => '#10B981',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Образование',
                'slug' => 'education',
                'description' => 'Кейсы продвижения образовательных платформ и онлайн-школ',
                'icon' => 'school',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Здравоохранение',
                'slug' => 'healthcare',
                'description' => 'Кейсы продвижения медицинских центров и клиник',
                'icon' => 'local_hospital',
                'color' => '#EF4444',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Финансы',
                'slug' => 'finance',
                'description' => 'Кейсы продвижения финтех стартапов и финансовых услуг',
                'icon' => 'account_balance',
                'color' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Недвижимость',
                'slug' => 'real-estate',
                'description' => 'Кейсы продвижения агентств недвижимости и застройщиков',
                'icon' => 'home',
                'color' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            IndustryCategory::create($categoryData);
        }
    }
}
