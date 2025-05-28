<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\Auth\IsAdmin;
use App\Http\Middleware\RedirectToLocale;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\BlogController;




Route::get('/', function () {
    return redirect()->route('portfolio.index', ['locale' => 'undefined']);
});

// lang prefix for all routes inside this block
Route::group(['prefix' => '{locale}', 'middleware' => RedirectToLocale::class], function () {
    // redirect '/' to portfolio.index
    Route::redirect('/', '/portfolio');

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified', IsAdmin::class])->name('dashboard');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/api.php';

Route::get('{path}', function ($path) {
    return response('', 404);
})->where('path', '.*')->middleware(RedirectToLocale::class);
