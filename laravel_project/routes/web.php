<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\IndustryCategoryController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\DemoController;


// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Services pages
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{service:slug}', [ServiceController::class, 'show'])->name('show');

});

// Cases
Route::prefix('cases')->name('cases.')->group(function () {
    Route::get('/', [CaseController::class, 'index'])->name('index');
    Route::get('/category/{industry}', [CaseController::class, 'category'])->name('category');
    Route::get('/{id}', [CaseController::class, 'show'])->name('show');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');
    Route::get('/{category}/{slug}', [BlogController::class, 'show'])->name('article');
    Route::get('/{slug}', [BlogController::class, 'showWithoutCategory'])->name('article.uncategorized');
});

// Static pages
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');

// Contact forms
Route::prefix('contact')->name('contact.')->group(function () {
    Route::post('/hero', [ContactController::class, 'submitHero'])->name('hero');
    Route::post('/', [ContactController::class, 'submitContact'])->name('submit');
});
Route::post('/service/order', [ContactController::class, 'submitServiceOrder'])->name('service.order');

// Media Library (protected routes)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/media', [MediaLibraryController::class, 'index'])->name('media.library');
});

// Demo routes
Route::get('/demo/media', [DemoController::class, 'index'])->name('demo.media');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);


// Additional static pages can be added here
// Route::get('/about', [PageController::class, 'about'])->name('about');
// Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');


// ============================================================================
// ADMIN ROUTES
// ============================================================================

// Admin API routes for categories (protected)
Route::prefix('api')->name('api.')->middleware(['auth', 'web'])->group(function () {
    // Blog Categories API
    Route::resource('blog-categories', BlogCategoryController::class);

    // Industry Categories API
    Route::resource('industry-categories', IndustryCategoryController::class);
});

// Остальные админ роуты в филаменте
