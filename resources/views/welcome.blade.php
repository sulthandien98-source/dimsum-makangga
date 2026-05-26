<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimsum Mak'Angga</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-[#f8fafc] text-gray-800 overflow-x-hidden">

    <!-- BACKGROUND BLUR -->
    <div class="fixed top-0 left-0 w-72 h-72 bg-orange-200 rounded-full blur-3xl opacity-30 -z-10"></div>
    <div class="fixed bottom-0 right-0 w-72 h-72 bg-red-200 rounded-full blur-3xl opacity-30 -z-10"></div>

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center text-white font-bold shadow-lg">
                        🥟
                    </div>

                    <div>
                        <h1 class="font-bold text-lg leading-none">
                            Dimsum Mak'Angga
                        </h1>

                        <p class="text-xs text-gray-500">
                            Fresh & Homemade
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">

                    @auth

                        @if(auth()->user()->role === 'admin')

                            <a href="{{ route('admin.dashboard') }}"
                               class="hidden sm:inline-flex px-5 py-2.5 rounded-xl bg-black text-white font-medium hover:scale-105 transition duration-300 shadow-lg">
                                Dashboard
                            </a>

                        @else

                            <a href="{{ route('menu') }}"
                               class="hidden sm:inline-flex px-5 py-2.5 rounded-xl bg-orange-500 text-white font-medium hover:bg-orange-600 hover:scale-105 transition duration-300 shadow-lg">
                                Lihat Menu
                            </a>

                        @endif

                    @else

                        <a href="{{ route('login') }}"
                           class="px-4 sm:px-5 py-2.5 rounded-xl bg-orange-500 text-white font-medium hover:bg-orange-600 hover:scale-105 transition duration-300 shadow-lg">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="hidden sm:inline-flex px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 font-medium transition duration-300">
                            Register
                        </a>

                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative py-16 sm:py-20 lg:py-28 overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <!-- LEFT -->
                <div class="text-center lg:text-left animate-fade-up">

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-100 text-orange-700 text-sm font-medium mb-6 shadow-sm">
                        🔥 Dimsum Premium Homemade
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight text-gray-900">
                        Dimsum Lezat,
                        <span class="bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">
                            Fresh Setiap Hari
                        </span>
                    </h1>

                    <p class="mt-6 text-base sm:text-lg text-gray-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Nikmati dimsum premium dengan rasa autentik,
                        harga terjangkau, dan pengiriman cepat langsung
                        ke rumah Anda.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">

                        <a href="{{ route('menu') }}"
                           class="px-7 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold shadow-xl hover:scale-105 hover:shadow-2xl transition duration-300 text-center">
                            🍽️ Pesan Sekarang
                        </a>

                        <a href="#promo"
                           class="px-7 py-4 rounded-2xl bg-white border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 hover:scale-105 transition duration-300 text-center shadow-md">
                            🔥 Lihat Promo
                        </a>
                    </div>

                    <!-- STATS -->
                    <div class="grid grid-cols-3 gap-4 mt-12">

                        <div class="bg-white rounded-2xl p-5 shadow-lg border border-gray-100 hover:-translate-y-1 transition duration-300">
                            <h3 class="text-2xl font-bold text-orange-500">500+</h3>
                            <p class="text-sm text-gray-500 mt-1">Pelanggan</p>
                        </div>

                        <div class="bg-white rounded-2xl p-5 shadow-lg border border-gray-100 hover:-translate-y-1 transition duration-300">
                            <h3 class="text-2xl font-bold text-red-500">4.9★</h3>
                            <p class="text-sm text-gray-500 mt-1">Rating</p>
                        </div>

                        <div class="bg-white rounded-2xl p-5 shadow-lg border border-gray-100 hover:-translate-y-1 transition duration-300">
                            <h3 class="text-2xl font-bold text-yellow-500">Fast</h3>
                            <p class="text-sm text-gray-500 mt-1">Delivery</p>
                        </div>

                    </div>
                </div>

                <!-- RIGHT -->
                <div class="relative flex justify-center animate-float">

                    <div class="absolute inset-0 bg-gradient-to-r from-orange-300 to-red-300 blur-3xl opacity-30 rounded-full"></div>

                    <div class="relative bg-white rounded-[2rem] p-6 shadow-2xl border border-gray-100 max-w-md w-full">

                        <img
                            src="https://images.unsplash.com/photo-1563245372-f21724e3856d?q=80&w=1200&auto=format&fit=crop"
                            class="rounded-3xl object-cover h-[300px] sm:h-[400px] w-full"
                            alt="Dimsum"
                        >

                        <div class="mt-6 flex items-center justify-between">

                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    Dimsum Mix Premium
                                </h3>

                                <p class="text-gray-500 mt-1">
                                    Fresh • Halal • Homemade
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-400 line-through">
                                    Rp 25.000
                                </p>

                                <p class="text-2xl font-bold text-orange-500">
                                    Rp 15K
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROMO -->
    <section id="promo" class="pb-20">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-[2rem] p-8 sm:p-12 shadow-2xl relative overflow-hidden">

                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 text-center text-white">

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 mb-6 backdrop-blur-lg">
                        🎉 PROMO TERBATAS
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                        Diskon Hingga 40%
                    </h2>

                    <p class="mt-4 text-white/90 max-w-2xl mx-auto text-base sm:text-lg leading-relaxed">
                        Pesan sekarang dan nikmati dimsum premium dengan harga spesial.
                        Promo hanya berlaku hari ini.
                    </p>

                    <div class="mt-8">
                        <a href="{{ route('menu') }}"
                           class="inline-flex px-8 py-4 rounded-2xl bg-white text-orange-600 font-bold shadow-2xl hover:scale-105 transition duration-300">
                            🍽️ Order Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>