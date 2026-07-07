<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(rand(4, 10)) . '?', // string
            'answer' => $this->faker->paragraphs(rand(1, 3), true), // text
            'sort_order' => $this->faker->numberBetween(0, 100), // integer default 0
            'is_active' => $this->faker->boolean(90), // boolean default true
        ];
    }

}
