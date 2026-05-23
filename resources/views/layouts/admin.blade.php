<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Admin — @yield('title', 'Dashboard') | Dimsum Mak'Angga
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine JS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            background: #0d0d0d;
            color: #e5e7eb;
            font-family: 'Nunito', sans-serif;
        }

        .admin-sidebar {
            background: #111;
            border-right: 1px solid #1f1f1f;
            width: 260px;
        }

        .admin-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
            color: #9ca3af;
            transition: .2s;
        }

        .admin-link:hover {
            background: #1f1f1f;
            color: white;
        }

        .admin-link.active {
            background: #f97316;
            color: white;
        }

        .admin-card {
            background: #161616;
            border: 1px solid #262626;
            border-radius: 16px;
            padding: 20px;
        }

        /* NOTIFICATION */
        .notif-dropdown {
            position: absolute;
            right: 0;
            top: 42px;
            width: 320px;
            background: #161616;
            border: 1px solid #262626;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,.4);
            z-index: 999;
        }

        .notif-item {
            padding: 12px;
            border-bottom: 1px solid #222;
            cursor: pointer;
        }

        .notif-item:hover {
            background: #1f1f1f;
        }

        /* TOAST */
        .btn-orange {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            border: none;
            cursor: pointer;
            transition: .2s;
        }

        .btn-orange:hover {
            opacity: .9;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f97316;
            color: white;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 700;
            z-index: 9999;
            animation: fadeIn .2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar flex flex-col justify-between p-5">

        <div>

            <!-- LOGO -->
            <div class="flex items-center gap-3 mb-8">

                <div
                    class="w-10 h-10 flex items-center justify-center rounded-xl"
                    style="background:linear-gradient(135deg,#f97316,#ea580c);">
                    🥟
                </div>

                <div>
                    <p class="font-black text-white">
                        Mak'Angga
                    </p>

                    <p class="text-xs text-orange-500 font-bold">
                        ADMIN PANEL
                    </p>
                </div>

            </div>

            <!-- MENU -->
            <nav class="space-y-2">

                <a href="{{ route('admin.dashboard') }}"
                   class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="admin-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    📋 Pesanan
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="admin-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    🍽️ Produk
                </a>

                <!-- FIX REKAPITULASI -->
                <a href="{{ route('admin.rekapitulasi.index') }}"
                   class="admin-link {{ request()->routeIs('admin.rekapitulasi.*') ? 'active' : '' }}">
                    📈 Rekapitulasi
                </a>

                <a href="{{ route('menu') }}"
                   class="admin-link">
                    🌐 Lihat Toko
                </a>

            </nav>

        </div>

        <!-- USER CARD -->
        <div class="admin-card">

            <p class="font-bold text-white mb-2">
                {{ auth()->user()->name ?? 'Administrator' }}
            </p>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full text-red-400 py-2 rounded-lg border border-red-500/30 hover:bg-red-900/30 transition">

                    Logout

                </button>
            </form>

        </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <div class="px-8 py-4 border-b border-[#1f1f1f] bg-[#111] flex justify-between items-center">

            <h1 class="text-xl font-black text-white">
                @yield('title')
            </h1>

            <!-- NOTIFICATION -->
            <div x-data="notifSystem()" x-init="init()" class="relative">

                <button @click="toggle()" class="text-xl relative">

                    🔔

                    <span
                        x-show="count > 0"
                        x-cloak
                        x-text="count"
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">
                    </span>

                </button>

                <!-- DROPDOWN -->
                <div
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    class="notif-dropdown"
                    x-cloak>

                    <div class="p-3 font-bold border-b border-[#222]">
                        Notifikasi
                    </div>

                    <template x-if="notifications.length === 0">
                        <p class="p-3 text-sm text-gray-400">
                            Belum ada notifikasi
                        </p>
                    </template>

                    <template x-for="n in notifications" :key="n.id">

                        <div
                            class="notif-item"
                            @click="goToOrder(n.id)">

                            <p class="font-bold text-sm">
                                Pesanan #<span x-text="n.id"></span>
                            </p>

                            <p class="text-xs text-gray-400">
                                Rp <span x-text="n.total"></span>
                            </p>

                        </div>

                    </template>

                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-8 flex-1">
            @yield('content')
        </div>

    </main>

</div>

<!-- SCRIPT -->
<script>

function showToast(message)
{
    const toast = document.createElement('div');

    toast.className = 'toast';

    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function notifSystem()
{
    return {

        open: false,

        notifications: [],

        count: 0,

        toggle()
        {
            this.open = !this.open;

            if (this.open) {
                this.count = 0;
            }
        },

        init()
        {
            const waitEcho = setInterval(() => {

                if (window.Echo) {

                    clearInterval(waitEcho);

                    this.listen();
                }

            }, 300);
        },

        listen()
        {
            window.Echo.channel('orders')
                .listen('.order.created', (e) => {

                    if (!e.order) return;

                    this.notifications.unshift({

                        id: e.order.id,

                        total: e.order.total_price

                    });

                    this.count++;

                    try {

                        new Audio('/notif.mp3')
                            .play()
                            .catch(() => {});

                    } catch {}

                    showToast(`Pesanan baru #${e.order.id}`);
                });
        },

        goToOrder(id)
        {
            window.location.href = `/admin/orders/${id}`;
        }
    }
}

</script>

</body>
</html>