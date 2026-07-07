<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Запускаем сидеры в правильном порядке
        $this->call([
            // Сначала создаем пользователей
            UserSeeder::class,

            // Затем создаем категории
            IndustryCategorySeeder::class,
            BlogCategorySeeder::class,

            // Затем создаем контент, который зависит от категорий
            ProjectCaseSeeder::class,
            BlogSeeder::class,
            ServiceSeeder::class,

            // Настройки и FAQ не зависят от других моделей
            ContactSettingSeeder::class,
            FaqSeeder::class,
            HeroSectionSeeder::class,
            WhyUsSeeder::class,
        ]);
    }
}
