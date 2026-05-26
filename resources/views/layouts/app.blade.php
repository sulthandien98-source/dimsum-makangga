<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Menu') — Dimsum Mak'Angga</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Nunito', sans-serif; }
        [x-cloak] { display: none !important; }

        :root {
            --bg-main: #1a0a00;
            --accent:  #f97316;
            --gold:    #fbbf24;
            --text:    #fef3c7;
        }

        body {
            background: radial-gradient(ellipse at top left, #2d1000 0%, #1a0800 40%, #0f0500 100%);
            min-height: 100vh;
            color: var(--text);
        }

        .card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,180,60,0.15);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 20px;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; border-radius: 12px;
            font-weight: 700; font-size: 0.9rem;
            transition: .2s;
        }

        .sidebar-link:hover {
            background: rgba(249,115,22,0.15);
            color: #fbbf24;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white; font-weight: 800;
            padding: 10px 24px; border-radius: 12px;
        }
    </style>
</head>

<body>
<div class="flex flex-col lg:flex-row min-h-screen">

    <!-- ===== SIDEBAR ===== -->
    <aside class="w-full lg:w-64 flex flex-col justify-between p-5"
           style="background: rgba(0,0,0,0.4); border-right:1px solid rgba(255,180,60,0.12);">

        <div>

            <!-- LOGO -->
            <a href="{{ route('menu') }}" class="block mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-2xl"
                         style="background:linear-gradient(135deg,#f97316,#ea580c);">🥟</div>
                    <div>
                        <p class="font-black text-lg" style="color:#fbbf24;">Dimsum</p>
                        <p class="text-xs opacity-50">Mak'Angga</p>
                    </div>
                </div>
            </a>

            <!-- MENU -->
            <nav class="space-y-1">

                <a href="{{ route('menu') }}"
                   class="sidebar-link {{ request()->routeIs('menu') ? 'active' : '' }}">
                    🏠 Menu
                </a>

                @auth
                <a href="{{ route('orders') }}"
                   class="sidebar-link {{ request()->routeIs('orders') ? 'active' : '' }}">
                    📦 Pesanan
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    👤 Profil
                </a>

                {{-- 🔥 ADMIN MENU --}}
                @if(auth()->user()->isAdmin())
                <hr class="my-3 border-orange-800">

                <p class="text-xs text-orange-400 font-bold px-2">ADMIN</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>

                <!-- ✅ FIX DI SINI -->
                <a href="{{ route('admin.products.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    🍱 Produk
                </a>

                <!-- ✅ FIX DI SINI -->
                <a href="{{ route('admin.orders.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    🧾 Pesanan
                </a>
                @endif

                @endauth

            </nav>
        </div>

        <!-- USER INFO -->
        <div>
            @auth
            <div class="card">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold"
                         style="background:linear-gradient(135deg,#f97316,#fbbf24); color:#1a0800;">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>

                    <div>
                        <p class="font-bold text-sm" style="color:#fbbf24;">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs opacity-60">
                            {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full py-2 rounded-xl text-sm font-bold"
                            style="background:rgba(220,38,38,.2); color:#fca5a5;">
                        Logout
                    </button>
                </form>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-primary block text-center">Login</a>
            @endauth
        </div>

    </aside>

    <!-- ===== MAIN ===== -->
    <main class="flex-1 w-full overflow-x-hidden">

        <!-- HEADER -->
        <div class="px-8 py-4 flex justify-between"
             style="border-bottom:1px solid rgba(255,180,60,0.1);">

            <h1 class="text-2xl font-black text-orange-400">
                @yield('page-title', '🍽️ Menu')
            </h1>

            @auth
            <p class="text-sm opacity-70">
                Halo, <span class="font-bold text-orange-400">{{ auth()->user()->name }}</span>
            </p>
            @endauth
        </div>

        <!-- FLASH -->
        @if(session('success'))
        <div class="m-4 p-3 rounded bg-green-600/20 text-green-300">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="m-4 p-3 rounded bg-red-600/20 text-red-300">
            {{ session('error') }}
        </div>
        @endif

        <div class="p-8">
            @yield('content')
        </div>

    </main>

</div>

</body>
</html>