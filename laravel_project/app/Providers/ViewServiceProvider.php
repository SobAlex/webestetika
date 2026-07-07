<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\IndustryCategory;
use App\Models\BlogCategory;
use App\Models\Contact;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Передаем активные категории во все представления
        View::composer('*', function ($view) {
            // Категории кейсов
            $activeCategories = IndustryCategory::active()
                ->ordered()
                ->get()
                ->map(function ($category) {
                    return [
                        'slug' => $category->slug,
                        'name' => $category->name,
                        'icon' => $category->icon ?: 'business',
                        'color' => $category->color,
                        'route' => 'cases.category',
                        'route_params' => [$category->slug]
                    ];
                })
                ->toArray();

            // Категории блогов
            $activeBlogCategories = BlogCategory::active()
                ->ordered()
                ->get()
                ->map(function ($category) {
                    return [
                        'slug' => $category->slug,
                        'name' => $category->name,
                        'icon' => $category->icon ?: 'article',
                        'color' => $category->color,
                        'description' => $category->description,
                        'route' => 'blog.category'
                    ];
                })
                ->toArray();

            // Контактные данные
            $contactData = Contact::getContactData();
            $contactInfo = [
                'address' => $contactData['address'] ?? 'Адрес не указан',
                'phone' => $contactData['phone'] ?? 'Телефон не указан',
                'email' => $contactData['email'] ?? 'Email не указан',
                'working_hours' => $contactData['working_hours'] ?? 'Часы работы не указаны',
                'social' => $contactData['social'] ?? []
            ];

            $view->with([
                'activeCategories' => $activeCategories,
                'activeBlogCategories' => $activeBlogCategories,
                'contactInfo' => $contactInfo
            ]);
        });
    }
}
