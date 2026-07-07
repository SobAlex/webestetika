<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(rand(1, 3), true), // string
            'slug' => $this->faker->unique()->slug(), // string unique
            'description' => $this->faker->paragraph(rand(1, 3)), // text nullable
            'color' => $this->faker->hexColor(), // string default '#06b6d4'
            'icon' => $this->faker->randomElement([ // string default 'article'
                'trending_up', 'analytics', 'tips_and_updates', 'code', 'description'
            ]),
            'is_active' => $this->faker->boolean(90), // boolean default true
            'sort_order' => $this->faker->numberBetween(0, 100), // integer default 0
        ];
    }

}
