<nav
    class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-lg dark:border-zinc-800 dark:bg-zinc-900/80">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-20 items-center justify-between">

            {{-- LOGO --}}
            <div class="flex items-center gap-3">

                <a href="{{ url('/') }}"
                    class="flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-xl font-bold text-white shadow-lg">

                        🥟

                    </div>

                    <div>
                        <h1 class="text-lg font-bold">
                            Dimsum Mak'Angga
                        </h1>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Fresh & Homemade
                        </p>
                    </div>

                </a>

            </div>

            {{-- DESKTOP MENU --}}
            <div class="hidden items-center gap-3 lg:flex">

                <a href="{{ url('/') }}"
                    class="rounded-xl px-4 py-2 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">
                    Home
                </a>

                <a href="{{ route('menu') }}
                    class="rounded-xl px-4 py-2 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">
                    Menu
                </a>

                <a href="{{ route('cart.get') }}"
                    class="rounded-xl px-4 py-2 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">
                    Keranjang
                </a>

                @auth

                    @if (auth()->user()->role === 'admin')

                        <a href="{{ route('admin.dashboard') }}"
                            class="rounded-xl px-4 py-2 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">
                            Dashboard
                        </a>

                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="rounded-xl bg-red-500 px-5 py-2 text-white transition duration-300 hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}"
                        class="rounded-xl bg-orange-500 px-5 py-2 text-white transition duration-300 hover:bg-orange-600">
                        Login
                    </a>

                @endauth

                {{-- DARK MODE --}}
                <button id="theme-toggle"
                    class="rounded-xl bg-gray-100 p-2 transition duration-300 hover:scale-105 dark:bg-zinc-800">

                    🌙

                </button>

            </div>

            {{-- MOBILE BUTTON --}}
            <div class="flex items-center gap-2 lg:hidden">

                <button id="theme-toggle-mobile"
                    class="rounded-xl bg-gray-100 p-2 transition duration-300 dark:bg-zinc-800">

                    🌙

                </button>

                <button id="mobile-menu-button"
                    class="rounded-xl bg-orange-500 p-2 text-white shadow-lg transition duration-300 hover:scale-105">

                    ☰

                </button>

            </div>

        </div>

        {{-- MOBILE MENU --}}
        <div id="mobile-menu"
            class="hidden space-y-3 rounded-2xl bg-white p-5 shadow-2xl dark:bg-zinc-900 lg:hidden mb-5">

            <a href="{{ url('/') }}"
                class="block rounded-xl px-4 py-3 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">

                Home

            </a>

            <a href="{{ route('menu') }}"
                class="block rounded-xl px-4 py-3 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">

                Menu

            </a>

            <a href="{{ route('cart.get') }}"
                class="block rounded-xl px-4 py-3 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">

                Keranjang

            </a>

            @auth

                @if (auth()->user()->role === 'admin')

                    <a href="{{ route('admin.dashboard') }}"
                        class="block rounded-xl px-4 py-3 transition duration-300 hover:bg-orange-100 dark:hover:bg-zinc-800">

                        Dashboard

                    </a>

                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="w-full rounded-xl bg-red-500 px-5 py-3 text-white transition duration-300 hover:bg-red-600">

                        Logout

                    </button>
                </form>

            @else

                <a href="{{ route('login') }}"
                    class="block rounded-xl bg-orange-500 px-5 py-3 text-center text-white transition duration-300 hover:bg-orange-600">

                    Login

                </a>

            @endauth

        </div>

    </div>

</nav>