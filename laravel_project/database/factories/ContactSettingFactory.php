<?php

namespace Database\Factories;

use App\Models\ContactSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactSetting>
 */
class ContactSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['text', 'email', 'phone', 'url']);

        return [
            'key' => $this->faker->unique()->slug(2), // string unique
            'value' => $this->generateValueByType($type), // text nullable - генерируется по типу
            'type' => $type, // string default 'text'
            'label' => $this->generateLabelByType($type), // string - генерируется по типу
            'description' => $this->faker->optional(0.7)->sentence(), // text nullable
            'is_active' => $this->faker->boolean(90), // boolean default true
            'sort_order' => $this->faker->numberBetween(0, 100), // integer default 0
        ];
    }

    /**
     * Generate value based on type.
     */
    private function generateValueByType(string $type): string
    {
        return match($type) {
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'url' => $this->faker->url(),
            'text' => $this->faker->sentence(),
            default => $this->faker->sentence(),
        };
    }

    /**
     * Generate label based on type.
     */
    private function generateLabelByType(string $type): string
    {
        return match($type) {
            'email' => $this->faker->randomElement(['Email', 'Электронная почта', 'Контактный email']),
            'phone' => $this->faker->randomElement(['Телефон', 'Номер телефона', 'Контактный телефон']),
            'url' => $this->faker->randomElement(['Сайт', 'Веб-сайт', 'Официальный сайт']),
            'text' => $this->faker->words(2, true),
            default => $this->faker->words(2, true),
        };
    }

    /**
     * Create an email contact setting.
     */
    public function email(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'email',
                'value' => $this->faker->email(),
                'label' => $this->faker->randomElement(['Email', 'Электронная почта', 'Контактный email']),
            ];
        });
    }

    /**
     * Create a phone contact setting.
     */
    public function phone(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'phone',
                'value' => $this->faker->phoneNumber(),
                'label' => $this->faker->randomElement(['Телефон', 'Номер телефона', 'Контактный телефон']),
            ];
        });
    }

    /**
     * Create a URL contact setting.
     */
    public function url(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'url',
                'value' => $this->faker->url(),
                'label' => $this->faker->randomElement(['Сайт', 'Веб-сайт', 'Официальный сайт']),
            ];
        });
    }

    /**
     * Create an active contact setting.
     */
    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Create an inactive contact setting.
     */
    public function inactive(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

}
