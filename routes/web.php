<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\Auth\IsAdmin;


// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('index.portfolio');

// lang prefix for all routes inside this block
Route::group(['prefix' => '{locale}'], function () {
    Route::get('/', function () {
        return Inertia::render('Portfolio');
    })->name('portfolio.index');

    Route::get('/test', function () {
        return Inertia::render('Test');
    })->name('test');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified', IsAdmin::class])->name('dashboard');
});




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/api.php';
