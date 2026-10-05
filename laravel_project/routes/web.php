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
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\MediaController;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;


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
Route::prefix('contact')->name('contact.')->middleware('throttle:10,1')->group(function () {
    Route::post('/hero', [ContactController::class, 'submitHero'])->name('hero');
    Route::post('/', [ContactController::class, 'submitContact'])->name('submit');
});
Route::post('/service/order', [ContactController::class, 'submitServiceOrder'])
    ->middleware('throttle:10,1')
    ->name('service.order');

// Media Library (protected routes)
Route::middleware([FilamentAuthenticate::class])->group(function () {
    Route::get('/admin/media', [MediaLibraryController::class, 'index'])->name('media.library');
    Route::post('/admin/media/upload', [MediaController::class, 'upload'])
        ->middleware('throttle:20,1')
        ->name('media.upload');
    Route::delete('/admin/media/{media}', [MediaController::class, 'destroy'])
        ->middleware('throttle:20,1')
        ->name('media.destroy');
});

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);


// Additional static pages can be added here
// Route::get('/about', [PageController::class, 'about'])->name('about');
// Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');


Route::view('/soglasie', 'pages.soglasie')->name('soglasie');
Route::view('/policy', 'pages.policy')->name('policy');
Route::view('/terms', 'pages.terms')->name('terms');

// Остальные админ роуты в филаменте
