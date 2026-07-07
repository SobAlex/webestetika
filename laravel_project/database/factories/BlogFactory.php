<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(rand(3, 8)), // string
            'slug' => $this->faker->unique()->slug(), // string unique
            'excerpt' => $this->faker->paragraph(rand(2, 4)), // text nullable
            'content' => $this->faker->paragraphs(rand(4, 8), true), // longText nullable
            'image' => $this->faker->randomElement(['human.jpeg', 'human2.jpeg', 'human.webp']), // string nullable
            'category_id' => BlogCategory::inRandomOrder()->first()?->id ?? 1, // foreignId - связь с BlogCategory
            'meta_title' => $this->faker->sentence(rand(4, 8)), // string nullable
            'meta_description' => $this->faker->paragraph(2), // text nullable
            'is_published' => $this->faker->boolean(80), // boolean default false
            'sort_order' => $this->faker->numberBetween(0, 100), // integer default 0
            'user_id' => 1, // Используем существующего пользователя
            'published_at' => $this->faker->optional(0.8)->dateTimeBetween('-1 year', 'now'), // timestamp nullable
        ];
    }

    /**
     * Indicate that the blog post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the blog post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the blog post is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'sort_order' => $this->faker->numberBetween(0, 10),
        ]);
    }
}
