<?php

namespace App\Providers;

use App\Services\BlogService;
use App\Services\CaseService;
use App\Services\ContactService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'activeCaseCategories' => app(CaseService::class)->getActiveCategories()->all(),
                'activeBlogCategories' => app(BlogService::class)->getActiveCategories()->all(),
                'contactInfo' => app(ContactService::class)->getContactInfo(),
            ]);
        });
    }
}
