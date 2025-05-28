<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class RedirectToLocale
{

    private function shareTranslations()
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('locales.fallback', 'es');

        if (file_exists(resource_path("lang/{$locale}.php"))) {
            $translations = include resource_path("lang/{$locale}.php");
        } else {
            $translations = include resource_path("lang/{$fallbackLocale}.php");
        }


        Inertia::share('trans', $translations);
    }

    /**
     * Handle an incoming request and redirect to the correct locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = config('locales.available', ['es', 'en', 'ca']);
        $fallbackLocale = config('locales.fallback', 'es');

        $userLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE') ?? '', 0, 2);
        $targetLocale = in_array($userLocale, $locales) ? $userLocale : $fallbackLocale;

        $path = $request->path();
        $segments = explode('/', $path);
        $firstSegment = $segments[0] ?? null;

        // Si el primer segmento es 'undefined', redirigir a la misma ruta sin ese segmento
        if ($firstSegment === 'undefined') {
            // Eliminar 'undefined' del path
            array_shift($segments);
            $newPath = implode('/', $segments);

            // Redirigir a la ruta con el locale adecuado
            return redirect("/{$targetLocale}" . ($newPath ? "/{$newPath}" : ""));
        }

        $firstSegmentInLocales = in_array($firstSegment, $locales);
        // dd the request path
        // dd($request->path());
        if (
            $firstSegmentInLocales ||
            $request->is('api/*') ||
            $request->is('storage/*') ||
            $request->is('assets/*') ||
            $request->is('admin/*')
        ) {
            // dd("sadsd");
            if ($firstSegmentInLocales) {
                app()->setLocale($firstSegment);
                $this->shareTranslations();
            }
            return $next($request);
        }

        app()->setLocale($targetLocale);
        $this->shareTranslations();

        $redirectPath = $path ? "/{$targetLocale}/{$path}" : "/{$targetLocale}";

        return redirect($redirectPath);
    }
}
