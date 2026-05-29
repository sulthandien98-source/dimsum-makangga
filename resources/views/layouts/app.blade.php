<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true',
        mobileMenu: false
    }"
    x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))"
    :class="{ 'dark': darkMode }"
>

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>{{ config('app.name') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-gray-100 dark:bg-gray-950 text-gray-800 dark:text-white transition-all duration-300">

    <!-- NAVBAR -->

    <nav class="sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-lg">

        <div class="container-ui">

            <div class="flex h-16 items-center justify-between">

                <!-- LOGO -->

                <a
                    href="/"
                    class="flex items-center gap-3"
                >

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-500 text-lg font-bold text-white shadow-lg">
                        DM
                    </div>

                    <div>

                        <h1 class="text-lg font-bold">
                            Dimsum Mak'Angga
                        </h1>

                        <p class="text-xs text-gray-500">
                            Modern Food Ordering
                        </p>

                    </div>

                </a>

                <!-- DESKTOP MENU -->

                <div class="hidden items-center gap-6 md:flex">

                    <a href="/" class="hover:text-orange-500 transition">
                        Home
                    </a>

                    <a href="/menu" class="hover:text-orange-500 transition">
                        Menu
                    </a>

                    <a href="/cart" class="hover:text-orange-500 transition">
                        Keranjang
                    </a>

                    @auth

                        <a href="/dashboard" class="hover:text-orange-500 transition">
                            Dashboard
                        </a>

                    @endauth

                    <button
                        @click="darkMode = !darkMode"
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gray-200 dark:bg-gray-800 transition hover:scale-110"
                    >
                        🌙
                    </button>

                </div>

                <!-- MOBILE BUTTON -->

                <button
                    @click="mobileMenu = !mobileMenu"
                    class="rounded-2xl bg-gray-200 p-2 dark:bg-gray-800 md:hidden"
                >
                    ☰
                </button>

            </div>

            <!-- MOBILE MENU -->

            <div
                x-show="mobileMenu"
                x-transition
                x-cloak
                class="space-y-4 py-5 md:hidden"
            >

                <a href="/" class="block">
                    Home
                </a>

                <a href="/menu" class="block">
                    Menu
                </a>

                <a href="/cart" class="block">
                    Keranjang
                </a>

                @auth

                    <a href="/dashboard" class="block">
                        Dashboard
                    </a>

                @endauth

                <button
                    @click="darkMode = !darkMode"
                    class="btn-secondary w-full"
                >
                    Toggle Dark Mode
                </button>

            </div>

        </div>

    </nav>

    <!-- CONTENT -->

    <main class="min-h-screen animate-fade">

        @yield('content')

    </main>

</body>

</html>