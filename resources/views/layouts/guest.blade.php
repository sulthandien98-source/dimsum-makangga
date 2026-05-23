<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', "Dimsum Mak'Angga") }}</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Poppins] antialiased">

    <!-- BACKGROUND DARK -->
    <div class="min-h-screen flex items-center justify-center px-4"
         style="background:#140a05;">

        <div class="w-full max-w-md">

            <!-- BRAND -->
            <div class="text-center mb-6">
                <a href="/" class="text-3xl font-black"
                   style="color:#fbbf24;">
                    🥟 Dimsum Mak'Angga
                </a>
                <p class="text-sm mt-1"
                   style="color:rgba(254,243,199,.6);">
                    Enak, Hangat, dan Terjangkau 🍜
                </p>
            </div>

            <!-- CARD GLASS -->
            <div class="p-6 rounded-2xl shadow-2xl fade-in"
                 style="background:rgba(0,0,0,0.5); border:1px solid rgba(255,180,60,0.15); backdrop-filter:blur(12px);">

                @yield('content')

            </div>

        </div>

    </div>

</body>
</html>