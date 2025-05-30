<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Auth\IsAdmin;

use Inertia\Inertia;

Route::group(['prefix' => 'admin', 'middleware' => [IsAdmin::class]], function () {
    // redirect to dashboard
    Route::redirect('/', '/admin/dashboard');

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    // Users Management
    Route::get('/users', function () {
        return Inertia::render('Admin/Users');
    })->name('admin.users');

    Route::get('/users/create', function () {
        return Inertia::render('Admin/Users/Create');
    })->name('admin.users.create');

    Route::get('/users/{id}', function ($id) {
        return Inertia::render('Admin/Users/Show', ['id' => $id]);
    })->name('admin.users.show');

    Route::get('/users/{id}/edit', function ($id) {
        return Inertia::render('Admin/Users/Edit', ['id' => $id]);
    })->name('admin.users.edit');

    // Projects Management
    Route::get('/projects', function () {
        return Inertia::render('Admin/Projects');
    })->name('admin.projects');

    Route::get('/projects/create', function () {
        return Inertia::render('Admin/Projects/Create');
    })->name('admin.projects.create');

    Route::get('/projects/{id}', function ($id) {
        return Inertia::render('Admin/Projects/Show', ['id' => $id]);
    })->name('admin.projects.show');

    Route::get('/projects/{id}/edit', function ($id) {
        return Inertia::render('Admin/Projects/Edit', ['id' => $id]);
    })->name('admin.projects.edit');

    // Blog Management
    Route::get('/blog', function () {
        return Inertia::render('Admin/Blog');
    })->name('admin.blog');

    Route::get('/blog/create', function () {
        return Inertia::render('Admin/Blog/Create');
    })->name('admin.blog.create');

    Route::get('/blog/categories', function () {
        return Inertia::render('Admin/Blog/Categories');
    })->name('admin.blog.categories');

    Route::get('/blog/{id}', function ($id) {
        return Inertia::render('Admin/Blog/Show', ['id' => $id]);
    })->name('admin.blog.show');

    Route::get('/blog/{id}/edit', function ($id) {
        return Inertia::render('Admin/Blog/Edit', ['id' => $id]);
    })->name('admin.blog.edit');

    // Settings
    Route::get('/settings', function () {
        return Inertia::render('Admin/Settings');
    })->name('admin.settings');

    // Activity Log
    Route::get('/activity', function () {
        return Inertia::render('Admin/Activity');
    })->name('admin.activity');
});