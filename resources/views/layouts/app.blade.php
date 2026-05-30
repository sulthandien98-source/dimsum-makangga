<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
        content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dimsum Mak\'Angga') }}</title>

    <link rel="icon"
        href="{{ asset('favicon.ico') }}">

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body
    class="bg-gray-50 text-gray-800 antialiased transition-all duration-300 dark:bg-zinc-950 dark:text-white">

    <div class="min-h-screen">

        @include('layouts.navigation')

        @if (isset($header))
            <header
                class="border-b border-gray-200 bg-white/80 backdrop-blur-lg dark:border-zinc-800 dark:bg-zinc-900/80">

                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>

            </header>
        @endif

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            {{-- SUCCESS TOAST --}}
            @if (session('success'))
                <div id="toast-success"
                    class="mb-6 rounded-2xl border border-green-200 bg-green-500 px-6 py-4 text-white shadow-xl">

                    {{ session('success') }}

                </div>
            @endif

            {{-- ERROR TOAST --}}
            @if (session('error'))
                <div id="toast-error"
                    class="mb-6 rounded-2xl border border-red-200 bg-red-500 px-6 py-4 text-white shadow-xl">

                    {{ session('error') }}

                </div>
            @endif

            {{ $slot ?? '' }}

            @yield('content')

        </main>

    </div>

    {{-- FLOATING CART MOBILE --}}
    @auth
        <a href="{{ route('checkout') }}"
            class="fixed bottom-5 right-5 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-orange-500 text-2xl text-white shadow-2xl transition duration-300 hover:scale-110 lg:hidden">

            🛒

        </a>
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /*
            |--------------------------------------------------------------------------
            | AUTO HIDE TOAST
            |--------------------------------------------------------------------------
            */

            const successToast =
                document.getElementById('toast-success');

            const errorToast =
                document.getElementById('toast-error');

            if (successToast) {

                setTimeout(() => {

                    successToast.remove();

                }, 3000);

            }

            if (errorToast) {

                setTimeout(() => {

                    errorToast.remove();

                }, 4000);

            }

            /*
            |--------------------------------------------------------------------------
            | DARK MODE
            |--------------------------------------------------------------------------
            */

            const html =
                document.documentElement;

            const desktopBtn =
                document.getElementById('theme-toggle');

            const mobileBtn =
                document.getElementById('theme-toggle-mobile');

            const savedTheme =
                localStorage.getItem('theme');

            if (savedTheme === 'dark') {

                html.classList.add('dark');

            }

            function toggleTheme() {

                html.classList.toggle('dark');

                localStorage.setItem(
                    'theme',
                    html.classList.contains('dark')
                    ? 'dark'
                    : 'light'
                );

            }

            desktopBtn?.addEventListener(
                'click',
                toggleTheme
            );

            mobileBtn?.addEventListener(
                'click',
                toggleTheme
            );

            /*
            |--------------------------------------------------------------------------
            | MOBILE MENU
            |--------------------------------------------------------------------------
            */

            const mobileButton =
                document.getElementById(
                    'mobile-menu-button'
                );

            const mobileMenu =
                document.getElementById(
                    'mobile-menu'
                );

            mobileButton?.addEventListener(
                'click',
                () => {

                    mobileMenu.classList.toggle(
                        'hidden'
                    );

                }
            );

        });
    </script>

</body>

</html>