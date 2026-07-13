<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SEO продвижение сайтов в интернете')</title>

    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Train+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Hurricane&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data>
    <div class="max-w-7xl mx-auto px-6">
        @include('partials.header')

        <main>
            @if (session('status'))
                <div class="sticky top-0 z-50 mb-4 border border-green-300 bg-green-50 text-green-800 px-4 py-3"
                    role="status" aria-live="polite">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @include('partials.modals')
    @include('cookie-consent::index')
</body>

</html>
