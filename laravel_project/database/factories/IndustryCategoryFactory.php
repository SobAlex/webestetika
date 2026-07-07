<?php

namespace Database\Factories;

use App\Models\IndustryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IndustryCategory>
 */
class IndustryCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IndustryCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(rand(1, 2), true), // string
            'slug' => $this->faker->unique()->slug(), // string unique
            'description' => $this->faker->paragraph(rand(1, 2)), // text nullable
            'icon' => $this->faker->randomElement([ // string nullable
                'business', 'store', 'factory', 'shopping_cart', 'devices',
                'directions_car', 'home', 'restaurant', 'local_hospital', 'school'
            ]),
            'color' => $this->faker->hexColor(), // string default '#3B82F6'
            'is_active' => $this->faker->boolean(90), // boolean default true
            'sort_order' => $this->faker->numberBetween(0, 100), // integer default 0
        ];
    }

}
