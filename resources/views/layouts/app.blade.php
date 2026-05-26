<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dimsum Mak\'Angga')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *{
            font-family:'Poppins',sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-extrabold text-orange-500">
                Dimsum Mak'Angga
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="/" class="hover:text-orange-500 transition">Home</a>
                <a href="/dashboard" class="hover:text-orange-500 transition">Dashboard</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6 fade-in">
        @yield('content')
    </main>

</body>
</html>
