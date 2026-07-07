<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeroSection>
 */
class HeroSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'image' => null, // Заполним в сидере конкретными изображениями
            'button_text' => fake()->randomElement([
                'Получить консультацию',
                'Заказать услугу',
                'Связаться с нами',
                'Узнать больше'
            ]),
            'is_active' => fake()->boolean(80), // 80% активных
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
