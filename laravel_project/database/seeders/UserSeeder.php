<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем пользователя только если его еще нет
        if (!User::where('email', 'soboldev@yandex.ru')->exists()) {
            User::create([
                'name' => 'SobolDev',
                'email' => 'soboldev@yandex.ru',
                'email_verified_at' => now(),
                'password' => Hash::make('123123123'),
            ]);
        }
    }
}
