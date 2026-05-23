<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimsum Mak'Angga</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* ANIMATION */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s ease forwards;
        }

        .fade-up.delay-1 { animation-delay: 0.2s; }
        .fade-up.delay-2 { animation-delay: 0.4s; }
        .fade-up.delay-3 { animation-delay: 0.6s; }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* HARD FIX BACKGROUND (ANTI FAIL) */
        .bg-main {
            background: linear-gradient(135deg, #ff4d4d, #ff7a18, #ffd700);
        }

        .glass {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>

<body class="bg-main min-h-screen flex items-center justify-center text-white relative overflow-hidden">

    <!-- GLOW BACKGROUND -->
    <div class="absolute w-[500px] h-[500px] bg-white/10 blur-3xl rounded-full top-[-100px] left-[-100px] animate-pulse"></div>
    <div class="absolute w-[400px] h-[400px] bg-yellow-300/20 blur-3xl rounded-full bottom-[-100px] right-[-100px] animate-pulse"></div>

    <!-- CONTENT -->
    <div class="relative text-center px-6 max-w-xl">

        <!-- TITLE -->
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 drop-shadow-lg fade-up">
            🥟 Dimsum Mak'Angga
        </h1>

        <!-- SUBTITLE -->
        <p class="text-white/90 mb-8 text-lg fade-up delay-1">
            Dimsum Enak, Murah & Kekinian 🔥 <br>
            Siap antar ke rumah kamu!
        </p>

        <!-- BUTTON -->
        <div class="flex justify-center gap-4 flex-wrap fade-up delay-2">

            @auth

                @if(auth()->user()->isAdmin())

                    <a href="{{ route('admin.dashboard') }}"
                       class="bg-black text-yellow-400 px-6 py-3 rounded-xl font-semibold 
                              hover:scale-105 transition shadow-xl">
                        👑 Dashboard Admin
                    </a>

                @else

                    <a href="{{ route('menu') }}"
                       class="bg-white text-red-500 px-6 py-3 rounded-xl font-semibold 
                              hover:scale-105 transition shadow-xl">
                        🍽️ Lihat Menu
                    </a>

                @endif

            @else

                <a href="{{ route('login') }}"
                   class="bg-black text-yellow-400 px-6 py-3 rounded-xl font-semibold 
                          hover:scale-105 transition shadow-xl">
                    🔐 Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-white/20 text-white px-6 py-3 rounded-xl font-semibold 
                          hover:bg-white/30 hover:scale-105 transition border border-white/30">
                    ✍️ Register
                </a>

            @endauth

        </div>

        <!-- PROMO -->
        <div class="mt-12 glass p-6 rounded-2xl shadow-2xl fade-up delay-3 hover:scale-105 transition">

            <h2 class="text-xl font-bold mb-3 text-yellow-200">
                🔥 Promo Hari Ini
            </h2>

            <p class="text-lg">
                Semua Dimsum hanya 
                <span class="bg-yellow-300 text-black px-2 py-1 rounded font-bold">
                    Rp 15.000
                </span>
            </p>

            <p class="text-white/80 text-sm mt-2">
                Buruan order sebelum kehabisan!
            </p>

        </div>

    </div>

</body>
</html>