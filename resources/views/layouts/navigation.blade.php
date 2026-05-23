<nav class="bg-white/20 backdrop-blur-xl border-b border-white/20 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">

        <!-- LEFT -->
        <div class="flex items-center gap-4">

            <!-- BACK BUTTON -->
            <button onclick="history.back()"
                class="bg-white/20 px-3 py-1 rounded-lg hover:bg-white/30 transition">
                ⬅ Back
            </button>

            <a href="/" class="font-bold text-yellow-300 text-lg">
                🥟 Dimsum
            </a>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-yellow-300">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('menu') }}" class="hover:text-yellow-300">
                        Menu
                    </a>
                @endif
            @endauth

        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-3">

            @auth

                <span class="text-sm text-white/80">
                    👋 {{ auth()->user()->name }}
                </span>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 px-3 py-1 rounded-lg hover:bg-red-600">
                        Logout
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}"
                   class="bg-yellow-400 text-black px-3 py-1 rounded-lg">
                    Login
                </a>

            @endauth

        </div>

    </div>
</nav>