@extends('layouts.app')

@section('title', 'Menu')
@section('page-title', '🍽️ Menu Kami')

@section('content')

<div x-data="cartApp()" x-init="init()" class="grid lg:grid-cols-4 gap-6">

    <!-- ===== PRODUK GRID ===== -->
    <div class="lg:col-span-3">

        @if(session('success'))
        <div x-data="{show:true}" x-init="setTimeout(()=>show=false,4000)" x-show="show"
             class="mb-5 p-3 rounded-xl text-sm font-bold"
             style="background:rgba(34,197,94,.15); border:1px solid rgba(34,197,94,.3); color:#86efac;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($products as $p)
        @php
            $available = $p->is_available && $p->stock > 0;
        @endphp
        <div class="relative rounded-2xl overflow-hidden transition-all duration-300 {{ $available ? 'hover:-translate-y-1' : 'opacity-60' }}"
             style="background:rgba(255,255,255,0.05); border:1px solid {{ $available ? 'rgba(255,180,60,0.15)' : 'rgba(255,255,255,0.06)' }};">

            <!-- IMAGE -->
            <div class="h-36 flex items-center justify-center text-5xl relative"
                 style="background: linear-gradient(135deg, rgba(249,115,22,0.2), rgba(251,191,36,0.15));">
                🥟
                @if(!$available)
                <div class="absolute inset-0 flex items-center justify-center"
                     style="background:rgba(0,0,0,0.6);">
                    <span class="text-sm font-black px-3 py-1 rounded-xl"
                          style="background:rgba(220,38,38,.8); color:white;">
                        HABIS
                    </span>
                </div>
                @endif
                @if($available && $p->stock <= 5)
                <div class="absolute top-2 right-2">
                    <span class="text-xs font-black px-2 py-0.5 rounded-lg"
                          style="background:rgba(249,115,22,.9); color:white;">
                        Sisa {{ $p->stock }}
                    </span>
                </div>
                @endif
            </div>

            <!-- INFO -->
            <div class="p-4">
                <h2 class="font-black text-base mb-1" style="color:#fbbf24;">{{ $p->name }}</h2>
                <p class="text-xs mb-3" style="color:rgba(254,243,199,.5);">
                    {{ $p->description ?: 'Dimsum spesial pilihan Mak\'Angga' }}
                </p>

                <div class="flex justify-between items-center">
                    <span class="font-black text-base" style="color:#f97316;">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </span>

                    @if($available)
                    <button @click="add({{ $p->id }})"
                            :disabled="adding === {{ $p->id }}"
                            class="w-9 h-9 rounded-xl font-black text-lg flex items-center justify-center transition"
                            style="background:linear-gradient(135deg,#f97316,#ea580c); color:white;"
                            :style="adding === {{ $p->id }} ? 'opacity:.5' : ''">
                        <span x-show="adding !== {{ $p->id }}">+</span>
                        <span x-show="adding === {{ $p->id }}">⏳</span>
                    </button>
                    @else
                    <button disabled class="w-9 h-9 rounded-xl font-black text-lg flex items-center justify-center"
                            style="background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.3);">
                        —
                    </button>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-3 text-center py-20" style="color:rgba(254,243,199,.4);">
            <p class="text-4xl mb-3">🥟</p>
            <p class="font-bold">Produk belum tersedia</p>
        </div>
        @endforelse

        </div>
    </div>

    <!-- ===== CART SIDEBAR ===== -->
    <div class="rounded-2xl p-5 h-fit sticky top-6"
         style="background:rgba(0,0,0,0.4); border:1px solid rgba(255,180,60,0.15);">

        <h2 class="font-black text-base mb-4 flex items-center gap-2" style="color:#fbbf24;">
            🛒 Keranjang
            @auth
            <span x-show="items.length > 0"
                  class="ml-auto text-xs px-2 py-0.5 rounded-full font-black"
                  style="background:#f97316; color:white;"
                  x-text="items.length"></span>
            @endauth
        </h2>

        @guest
        {{-- GUEST: tampilkan CTA login --}}
        <div class="text-center py-4">
            <p class="text-sm mb-4" style="color:rgba(254,243,199,.5);">
                Login untuk mulai memesan 🍽️
            </p>
            <a href="{{ route('login') }}"
               class="block py-2.5 rounded-xl font-black text-sm mb-2"
               style="background:linear-gradient(135deg,#f97316,#ea580c); color:white;">
                🔐 Login
            </a>
            <a href="{{ route('register') }}"
               class="block py-2.5 rounded-xl font-black text-sm"
               style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
                ✍️ Daftar Baru
            </a>
        </div>
        @endguest

        @auth
        {{-- ERROR NOTIF --}}
        <div x-show="cartError" x-cloak
             class="mb-3 p-2 rounded-xl text-xs font-bold text-center"
             style="background:rgba(220,38,38,.15); border:1px solid rgba(220,38,38,.3); color:#fca5a5;">
            <span x-text="cartError"></span>
        </div>

        {{-- EMPTY --}}
        <template x-if="items.length === 0">
            <p class="text-center text-sm py-4" style="color:rgba(254,243,199,.4);">
                Belum ada item 😢
            </p>
        </template>

        {{-- ITEMS --}}
        <template x-for="item in items" :key="item.id">
            <div class="flex justify-between items-center mb-3 text-sm">
                <div class="flex-1 pr-2">
                    <p class="font-bold leading-tight" style="color:#fef3c7;" x-text="item.name"></p>
                    <p class="text-xs" style="color:rgba(249,115,22,.8);"
                       x-text="'Rp ' + item.price.toLocaleString('id-ID')"></p>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="update(item.id,'minus')"
                            class="w-7 h-7 rounded-lg font-black flex items-center justify-center text-sm transition"
                            style="background:rgba(255,255,255,.1); color:#fef3c7;"
                            onmouseover="this.style.background='rgba(255,255,255,.2)'"
                            onmouseout="this.style.background='rgba(255,255,255,.1)'">−</button>
                    <span class="w-6 text-center font-black" style="color:#fbbf24;" x-text="item.qty"></span>
                    <button @click="update(item.id,'plus')"
                            class="w-7 h-7 rounded-lg font-black flex items-center justify-center text-sm transition"
                            style="background:#f97316; color:white;"
                            onmouseover="this.style.background='#ea580c'"
                            onmouseout="this.style.background='#f97316'">+</button>
                </div>
            </div>
        </template>

        <template x-if="items.length > 0">
            <div>
                <div class="border-t my-3" style="border-color:rgba(255,180,60,0.15);"></div>
                <div class="flex justify-between font-black mb-4">
                    <span style="color:rgba(254,243,199,.7);">Total</span>
                    <span style="color:#f97316;" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                </div>
                <a href="{{ route('checkout') }}"
                   class="block text-center py-3 rounded-xl font-black text-sm"
                   style="background:linear-gradient(135deg,#f97316,#ea580c); color:white;">
                    Checkout →
                </a>
                <button @click="clearCart()" class="w-full mt-2 text-xs py-2 rounded-xl font-bold transition"
                        style="color:rgba(254,243,199,.4);"
                        onmouseover="this.style.color='#fca5a5'"
                        onmouseout="this.style.color='rgba(254,243,199,.4)'">
                    🗑 Kosongkan keranjang
                </button>
            </div>
        </template>
        @endauth

    </div>

</div>

<!-- ===== MODAL GUEST LOGIN ===== -->
<div id="loginModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0" style="background:rgba(0,0,0,.7); backdrop-filter:blur(6px);"
         onclick="document.getElementById('loginModal').classList.add('hidden')"></div>
    <div class="relative rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl"
         style="background:#1a0800; border:1px solid rgba(249,115,22,.3);">
        <div class="text-5xl mb-4">🔐</div>
        <h3 class="text-xl font-black mb-1" style="color:#fbbf24;">Mau Pesan?</h3>
        <p class="text-sm mb-6" style="color:rgba(254,243,199,.6);">
            Kamu perlu login dulu untuk menambahkan ke keranjang.
        </p>
        <a href="{{ route('login') }}"
           class="block py-3 rounded-xl font-black text-sm mb-3"
           style="background:linear-gradient(135deg,#f97316,#ea580c); color:white;">
            🔐 Login Sekarang
        </a>
        <a href="{{ route('register') }}"
           class="block py-3 rounded-xl font-black text-sm"
           style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
            ✍️ Daftar Gratis
        </a>
        <button onclick="document.getElementById('loginModal').classList.add('hidden')"
                class="mt-4 text-xs" style="color:rgba(254,243,199,.3);">
            Tutup
        </button>
    </div>
</div>

<script>
const IS_AUTH = {{ auth()->check() ? 'true' : 'false' }};

function cartApp() {
    return {
        cart: {}, adding: null, cartError: null,

        async init() {
            try {
                const r = await fetch('/cart');
                this.cart = await r.json();
            } catch(e) {
                console.error(e);
                this.cart = {};
            }
        },

        get items() {
            return Object.entries(this.cart).map(([id, item]) => ({...item, id: Number(id)}));
        },
        get total() {
            return this.items.reduce((t, i) => t + i.qty * i.price, 0);
        },

        async add(id) {
             
            this.adding = id; this.cartError = null;
            try {
                const r = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({id})
                });
                if (r.status === 401) { window.location.href = '{{ route("login") }}'; return; }
                if (r.status === 422) {
                    const d = await r.json();
                    this.cartError = d.error || 'Stok tidak mencukupi';
                    setTimeout(() => this.cartError = null, 3000);
                    return;
                }
                this.cart = await r.json();
            } catch(e) { console.error(e); }
            finally { this.adding = null; }
        },

        async update(id, action) {
             
            try {
                const r = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({id, action})
                });
                if (r.status === 401) { window.location.href = '{{ route("login") }}'; return; }
                this.cart = await r.json();
            } catch(e) { console.error(e); }
        },

        async clearCart() {
             
            try {
                const r = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
                });
                this.cart = await r.json();
            } catch(e) { console.error(e); }
        }
    }
}
</script>

@endsection