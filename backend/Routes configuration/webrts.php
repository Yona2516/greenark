<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Serve the frontend application
Route::get('/{any}', function () {
    return file_get_contents(public_path('frontend/index.html'));
})->where('any', '.*');