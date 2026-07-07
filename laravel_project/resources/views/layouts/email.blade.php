<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Сообщение с сайта')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom styles for email -->
    <style>
        /* Ensure proper rendering in email clients */
        body {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Fix for Outlook */
        .outlook-group-fix {
            width: 100% !important;
        }

        /* Ensure images are responsive */
        img {
            max-width: 100% !important;
            height: auto !important;
        }
    </style>
</head>

<body class="font-sans text-gray-900 bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4">
            @yield('content')
        </div>
    </div>
</body>

</html>
