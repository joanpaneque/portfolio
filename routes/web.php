<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\Auth\IsAdmin;
use App\Http\Middleware\RedirectToLocale;
use App\Http\Controllers\PortfolioController;
// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('index.portfolio');


// outside test
Route::get('/outside', function () {
    return Inertia::render('Outside');
})->name('outside');



// redirect '/' to portfolio.index
Route::get('/', function () {
    return redirect()->route('portfolio.index', ['locale' => 'undefined']);
});

// lang prefix for all routes inside this block
Route::group(['prefix' => '{locale}', 'middleware' => RedirectToLocale::class], function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index');

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
