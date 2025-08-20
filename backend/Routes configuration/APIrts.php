<?php
// routes/api.php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\QuoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Public API endpoints
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::get('/featured', [ProjectController::class, 'featured']);
        Route::get('/categories', [ProjectController::class, 'categories']);
        Route::get('/{slug}', [ProjectController::class, 'show']);
    });

    Route::prefix('quotes')->group(function () {
        Route::post('/', [QuoteController::class, 'store']);
        Route::get('/track/{referenceNumber}', [QuoteController::class, 'track']);
    });

    Route::post('/contact', [ContactController::class, 'store']);
});