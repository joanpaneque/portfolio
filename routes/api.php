<?php

use Illuminate\Support\Facades\Route;

// route group with prefix api
Route::group(['prefix' => 'api'], function () {
    Route::get('/test.json', function () {
        return response()->json([
            'message' => 'Hello, world!',
        ]);
    });
});