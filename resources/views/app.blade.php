<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Metatags de accesibilidad y SEO -->
        <meta name="theme-color" content="#1e40af">
        <meta name="color-scheme" content="dark light">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <title inertia>{{ config('app.name', 'Joan Paneque') }}</title>
        
        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $page['props']['meta']['description'] ?? 'Portfolio de Joan Paneque - Desarrollador web y programador especializado en Laravel y Vue.js. Creador de aplicaciones web robustas e innovadoras con impacto real.' }}">
        <meta name="keywords" content="{{ $page['props']['meta']['keywords'] ?? 'joan paneque, desarrollador, programador, portfolio, web, javascript, php, laravel, vue, fullstack, desarrollo web, ingeniero software' }}">
        <meta name="author" content="Joan Paneque">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
        <link rel="canonical" href="{{ url()->current() }}">
        
        <!-- Alternate languages -->
        <link rel="alternate" hreflang="es" href="https://joanpaneque.dev/es">
        <link rel="alternate" hreflang="en" href="https://joanpaneque.dev/en">
        <link rel="alternate" hreflang="fr" href="https://joanpaneque.dev/fr">
        <link rel="alternate" hreflang="de" href="https://joanpaneque.dev/de">
        <link rel="alternate" hreflang="ca" href="https://joanpaneque.dev/ca">
        <link rel="alternate" hreflang="x-default" href="https://joanpaneque.dev">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $page['props']['meta']['title'] ?? config('app.name', 'Joan Paneque') }}">
        <meta property="og:description" content="{{ $page['props']['meta']['description'] ?? 'Portfolio de Joan Paneque - Desarrollador web y programador especializado en Laravel y Vue.js. Creador de aplicaciones web robustas e innovadoras con impacto real.' }}">
        <meta property="og:image" content="{{ $page['props']['meta']['image'] ?? asset('images/preview.jpg') }}">
        <meta property="og:image:alt" content="Joan Paneque - Portfolio">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta property="og:site_name" content="Joan Paneque">
        
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ $page['props']['meta']['title'] ?? config('app.name', 'Joan Paneque') }}">
        <meta name="twitter:description" content="{{ $page['props']['meta']['description'] ?? 'Portfolio de Joan Paneque - Desarrollador web y programador especializado en Laravel y Vue.js. Creador de aplicaciones web robustas e innovadoras con impacto real.' }}">
        <meta name="twitter:image" content="{{ $page['props']['meta']['image'] ?? asset('images/preview.jpg') }}">
        <meta name="twitter:image:alt" content="Joan Paneque - Portfolio">
        <meta name="twitter:creator" content="@joanpaneque">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
