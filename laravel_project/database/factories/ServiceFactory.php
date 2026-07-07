<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'SEO продвижение',
            'Контекстная реклама',
            'Веб-разработка',
            'Мобильные приложения',
            'SMM продвижение',
            'Email маркетинг',
            'Аналитика и аудит',
            'Брендинг и дизайн',
            'Консультации',
            'Техническая поддержка'
        ]);

        $icons = [
            'trending_up', 'ads_click', 'web', 'phone_android', 'share',
            'email', 'analytics', 'palette', 'support_agent', 'build'
        ];

        $colors = ['#06b6d4', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];

        $features = [
            'Профессиональный подход',
            'Индивидуальное решение',
            'Техническая поддержка',
            'Гарантия результата',
            'Современные технологии',
            'Опытная команда'
        ];

        return [
            'title' => $title . ' ' . fake()->unique()->numberBetween(1, 1000),
            'slug' => \Illuminate\Support\Str::slug($title . '-' . fake()->unique()->numberBetween(1, 1000)),
            'description' => fake()->sentence(10),
            'content' => fake()->paragraphs(5, true),
            'icon' => fake()->randomElement($icons),
            'color' => fake()->randomElement($colors),
            'image' => null, // Заполним позже или в сидере
            'price_from' => fake()->randomElement([null, fake()->numberBetween(10000, 500000)]),
            'price_type' => fake()->randomElement(['project', 'hour', 'month']),
            'features' => fake()->randomElements($features, fake()->numberBetween(3, 5)),
            'meta_title' => $title . ' - Профессиональные услуги',
            'meta_description' => fake()->sentence(15),
            'meta_keywords' => implode(', ', fake()->words(8)),
            'is_published' => fake()->boolean(80), // 80% опубликованы
            'is_featured' => fake()->boolean(30), // 30% рекомендуемые
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
