<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dimsum Mak\'Angga')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 text-slate-800">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="container-main">
            <div class="flex items-center justify-between h-16">
                <div class="font-extrabold text-xl text-orange-500">
                    Dimsum Mak'Angga
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <a href="/" class="hover:text-orange-500 transition">Home</a>
                    <a href="/menu" class="hover:text-orange-500 transition">Menu</a>
                    <a href="/cart" class="hover:text-orange-500 transition">Keranjang</a>
                </div>

                <button class="md:hidden">
                    ☰
                </button>
            </div>
        </div>
    </nav>

    <main class="container-main py-6 animate-fade">
        @yield('content')
    </main>

</body>
</html>
