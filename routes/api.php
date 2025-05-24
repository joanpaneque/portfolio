<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

Route::group(['prefix' => 'api'], function() {
    Route::post('/recommend-technology', [PortfolioController::class, 'recommendTechnology'])->name('api.recommend-technology');
    Route::post('/undo-recommendation', [PortfolioController::class, 'undoRecommendation'])->name('api.undo-recommendation');
    Route::post('/contact', [PortfolioController::class, 'storeContact'])->name('api.contact.store');
});