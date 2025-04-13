<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TimezoneController;

// Función para detectar el idioma preferido del navegador
function detectPreferredLanguage(Request $request) {
    $supportedLocales = ['es', 'ca', 'en', 'fr', 'de'];
    
    // Verificar si hay un idioma guardado en la sesión
    if ($request->session()->has('locale')) {
        $locale = $request->session()->get('locale');
        if (in_array($locale, $supportedLocales)) {
            return $locale;
        }
    }
    
    // Verificar el idioma del navegador
    $browserLocales = $request->server('HTTP_ACCEPT_LANGUAGE');
    if ($browserLocales) {
        $browserLocales = explode(',', $browserLocales);
        foreach ($browserLocales as $browserLocale) {
            $lang = substr($browserLocale, 0, 2);
            if (in_array($lang, $supportedLocales)) {
                return $lang;
            }
        }
    }
    
    // Por defecto, español
    return 'es';
}

// Ruta raíz que redirecciona al idioma detectado
Route::get('/', function (Request $request) {
    $locale = detectPreferredLanguage($request);
    return redirect()->to("/{$locale}");
});

// Ruta para español (ahora tiene su propia ruta)
Route::get('/es', function () {
    App::setLocale('es');
    return Inertia::render('Home', [
        'locale' => 'es'
    ]);
});

// Rutas para otros idiomas
Route::get('/en', function () {
    App::setLocale('en');
    return Inertia::render('Home', [
        'locale' => 'en'
    ]);
});

Route::get('/fr', function () {
    App::setLocale('fr');
    return Inertia::render('Home', [
        'locale' => 'fr'
    ]);
});

Route::get('/de', function () {
    App::setLocale('de');
    return Inertia::render('Home', [
        'locale' => 'de'
    ]);
});

Route::get('/ca', function () {
    App::setLocale('ca');
    return Inertia::render('Home', [
        'locale' => 'ca'
    ]);
});

// API para detectar zona horaria por IP
Route::get('/api/timezone', [TimezoneController::class, 'detectTimezone'])->name('api.timezone');

// Rutas de administración
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Rutas protegidas de administración
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Rutas de gestión de disponibilidad
    Route::get('/admin/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('admin.availability');
    Route::post('/admin/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'update'])->name('admin.availability.update');
});

// Ruta API para obtener la disponibilidad (sin protección de admin)
Route::get('/api/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'getAvailability'])->name('api.availability');
