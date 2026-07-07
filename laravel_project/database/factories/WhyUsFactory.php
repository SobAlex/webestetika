<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WhyUs>
 */
class WhyUsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'icon' => fake()->randomElement([
                'verified',
                'trending_up',
                'speed',
                'security',
                'support',
                'analytics',
                'thumb_up',
                'star',
                'rocket_launch',
                'workspace_premium'
            ]),
            'color' => fake()->randomElement([
                '#06b6d4',
                '#8b5cf6',
                '#f59e0b',
                '#10b981',
                '#ef4444',
                '#3b82f6'
            ]),
            'is_active' => fake()->boolean(90), // 90% активных
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
