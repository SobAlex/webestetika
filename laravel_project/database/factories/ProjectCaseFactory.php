<?php

namespace Database\Factories;

use App\Models\IndustryCategory;
use App\Models\ProjectCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectCase>
 */
class ProjectCaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectCase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'case_id' => 'seo-case-' . $this->faker->unique()->numberBetween(1, 1000), // string
            'title' => $this->faker->sentence(rand(3, 6)), // string
            'client' => $this->faker->company(), // string
            'industry_category_id' => IndustryCategory::inRandomOrder()->first()?->id ?? 1, // foreignId - связь с IndustryCategory
            'period' => $this->faker->randomElement(['6 месяцев', '8 месяцев', '10 месяцев', '12 месяцев', '7 месяцев', '9 месяцев', '11 месяцев']), // string
            'image' => $this->faker->randomElement(['human.jpeg', 'human2.jpeg', 'human.webp']), // string - используем существующие изображения
            'description' => $this->faker->paragraphs(rand(2, 4), true), // text
            'result_1' => $this->faker->sentence(rand(5, 10)), // string
            'result_2' => $this->faker->sentence(rand(5, 10)), // string
            'result_3' => $this->faker->sentence(rand(5, 10)), // string
            'result_4' => $this->faker->sentence(rand(5, 10)), // string
            'result_5' => $this->faker->optional(0.7)->sentence(rand(5, 10)), // string nullable
            'result_6' => $this->faker->optional(0.5)->sentence(rand(5, 10)), // string nullable
            'before_after' => [ // json
                'traffic' => ['before' => $this->faker->numberBetween(1000, 5000), 'after' => $this->faker->numberBetween(10000, 50000)],
                'keywords' => ['before' => $this->faker->numberBetween(10, 50), 'after' => $this->faker->numberBetween(100, 500)],
                'conversion' => ['before' => $this->faker->randomFloat(1, 1, 3), 'after' => $this->faker->randomFloat(1, 4, 8)],
                'revenue' => ['before' => $this->faker->numberBetween(100, 500), 'after' => $this->faker->numberBetween(1000, 3000)]
            ],
            'service_key' => 'seo-promotion', // string
            'is_published' => $this->faker->boolean(90), // boolean
            'sort_order' => $this->faker->numberBetween(0, 100), // integer
            'user_id' => 1, // Используем существующего пользователя
        ];
    }

}
