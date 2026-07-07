<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestMediaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Media Library API (protected)
Route::prefix('media')->middleware(['auth:sanctum'])->group(function () {
    Route::post('upload', [TestMediaController::class, 'upload']);
    Route::get('stats', [TestMediaController::class, 'stats']);
});
