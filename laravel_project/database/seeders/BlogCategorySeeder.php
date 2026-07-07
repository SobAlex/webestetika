<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем категории блогов
        $categories = [
            [
                'name' => 'SEO',
                'slug' => 'seo',
                'description' => 'Статьи о SEO продвижении и оптимизации сайтов',
                'icon' => 'trending_up',
                'color' => '#10B981',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Маркетинг',
                'slug' => 'marketing',
                'description' => 'Статьи о digital-маркетинге и продвижении',
                'icon' => 'campaign',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Аналитика',
                'slug' => 'analytics',
                'description' => 'Аналитические статьи и исследования',
                'icon' => 'analytics',
                'color' => '#06B6D4',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Разработка',
                'slug' => 'development',
                'description' => 'Статьи о веб-разработке и технологиях',
                'icon' => 'code',
                'color' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            BlogCategory::create($categoryData);
        }
    }
}
