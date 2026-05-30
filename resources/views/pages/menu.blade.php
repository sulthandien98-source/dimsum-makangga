@extends('layouts.app')

@section('content')

<div
    x-data="cartApp()"
    x-init="init()"
    class="container-ui py-8"
>

    <!-- HEADER -->

    <div class="mb-10 text-center">

        <h1 class="section-title mb-4">
            Menu Dimsum Mak'Angga
        </h1>

        <p class="text-muted max-w-2xl mx-auto">
            Nikmati dimsum premium dengan rasa autentik,
            tampilan modern, dan pelayanan terbaik.
        </p>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">

        <!-- PRODUCT -->

        <div class="xl:col-span-3">

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($products as $p)

                    @php
                        $available =
                            $p->is_available &&
                            $p->stock > 0;
                    @endphp

                    <div class="card-ui group hover:-translate-y-2 transition-all duration-300">

                        <!-- IMAGE -->

                        <div class="relative">

                            <div class="h-52 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-7xl">

                                🥟

                            </div>

                            @if(!$available)

                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center">

                                    <span class="bg-red-500 text-white text-sm px-4 py-2 rounded-2xl font-bold">
                                        Habis
                                    </span>

                                </div>

                            @endif

                        </div>

                        <!-- BODY -->

                        <div class="p-5">

                            <div class="mb-3">

                                <h2 class="text-xl font-bold mb-2">
                                    {{ $p->name }}
                                </h2>

                                <p class="text-muted text-sm line-clamp-2">
                                    {{ $p->description ?: 'Dimsum spesial premium Mak\'Angga.' }}
                                </p>

                            </div>

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-orange-500 font-black text-2xl">
                                        Rp {{ number_format($p->price,0,',','.') }}
                                    </p>

                                    <p class="text-xs text-muted">
                                        Stok:
                                        {{ $p->stock }}
                                    </p>

                                </div>

                                @if($available)

                                    <button
                                        @click="add({{ $p->id }})"
                                        :disabled="adding === {{ $p->id }}"
                                        class="btn-primary"
                                    >

                                        <span x-show="adding !== {{ $p->id }}">
                                            + Keranjang
                                        </span>

                                        <span x-show="adding === {{ $p->id }}">
                                            Loading...
                                        </span>

                                    </button>

                                @else

                                    <button
                                        disabled
                                        class="btn-secondary opacity-50"
                                    >
                                        Habis
                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-span-3">

                        <div class="card-ui p-10 text-center">

                            <div class="text-7xl mb-5">
                                🥟
                            </div>

                            <h2 class="text-2xl font-bold mb-2">
                                Produk Belum Tersedia
                            </h2>

                            <p class="text-muted">
                                Silakan tambahkan produk terlebih dahulu.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

        <!-- CART -->

        <div>

            <div class="card-ui p-6 sticky top-24">

                <div class="flex items-center justify-between mb-6">

                    <h2 class="text-2xl font-bold">
                        Keranjang
                    </h2>

                    <div
                        class="bg-orange-500 text-white text-sm font-bold px-3 py-1 rounded-xl"
                        x-text="items.length"
                    ></div>

                </div>

                <!-- EMPTY -->

                <template x-if="items.length === 0">

                    <div class="text-center py-10">

                        <div class="text-6xl mb-4">
                            🛒
                        </div>

                        <p class="text-muted">
                            Keranjang masih kosong
                        </p>

                    </div>

                </template>

                <!-- ITEMS -->

                <template x-if="items.length > 0">

                    <div>

                        <template
                            x-for="item in items"
                            :key="item.id"
                        >

                            <div class="border-b border-gray-200 dark:border-gray-800 py-4">

                                <div class="flex justify-between mb-2">

                                    <div>

                                        <h3
                                            class="font-bold"
                                            x-text="item.name"
                                        ></h3>

                                        <p
                                            class="text-orange-500 font-bold"
                                            x-text="'Rp ' + item.price.toLocaleString('id-ID')"
                                        ></p>

                                    </div>

                                    <div class="flex items-center gap-2">

                                        <button
                                            @click="update(item.id,'minus')"
                                            class="w-8 h-8 rounded-xl bg-gray-200 dark:bg-gray-800"
                                        >
                                            -
                                        </button>

                                        <span
                                            class="font-bold"
                                            x-text="item.qty"
                                        ></span>

                                        <button
                                            @click="update(item.id,'plus')"
                                            class="w-8 h-8 rounded-xl bg-orange-500 text-white"
                                        >
                                            +
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </template>

                        <!-- TOTAL -->

                        <div class="pt-6">

                            <div class="flex justify-between mb-5">

                                <span class="font-bold text-lg">
                                    Total
                                </span>

                                <span
                                    class="font-black text-2xl text-orange-500"
                                    x-text="'Rp ' + total.toLocaleString('id-ID')"
                                ></span>

                            </div>

                            @auth

                                <a
                                    href="{{ route('checkout') }}"
                                    class="btn-primary w-full"
                                >
                                    Checkout
                                </a>

                                <button
                                    @click="clearCart()"
                                    class="btn-secondary w-full mt-3"
                                >
                                    Kosongkan
                                </button>

                            @else

                                <a
                                    href="{{ route('login') }}"
                                    class="btn-primary w-full"
                                >
                                    Login Untuk Checkout
                                </a>

                            @endauth

                        </div>

                    </div>

                </template>

            </div>

        </div>

    </div>

</div>

<script>
function cartApp() {

    return {

        cart: {},
        adding: null,

        async init() {

            try {

                const response = await fetch('/cart', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil cart');
                }

                this.cart = await response.json();

            } catch (error) {

                console.error('Cart Init Error:', error);

                this.cart = {};
            }
        },

        get items() {

            return Object.entries(this.cart).map(([id, item]) => ({
                id: Number(id),
                name: item.name,
                qty: Number(item.qty),
                price: Number(item.price),
            }));
        },

        get total() {

            return this.items.reduce((sum, item) => {
                return sum + (item.qty * item.price);
            }, 0);
        },

        async add(id) {

            this.adding = id;

            try {

                const response = await fetch('/cart/add', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },

                    body: JSON.stringify({
                        id: id
                    })

                });

                if (!response.ok) {
                    throw new Error('Gagal menambah produk');
                }

                this.cart = await response.json();

            } catch (error) {

                console.error('Add Cart Error:', error);

                alert('Gagal menambahkan produk ke keranjang.');

            } finally {

                this.adding = null;
            }
        },

        async update(id, action) {

            try {

                const response = await fetch('/cart/update', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },

                    body: JSON.stringify({
                        id,
                        action
                    })

                });

                if (!response.ok) {
                    throw new Error('Gagal update cart');
                }

                this.cart = await response.json();

            } catch (error) {

                console.error('Update Cart Error:', error);

                alert('Gagal mengupdate keranjang.');
            }
        },

        async clearCart() {

            try {

                const response = await fetch('/cart/clear', {

                    method: 'POST',

                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    }

                });

                if (!response.ok) {
                    throw new Error('Gagal mengosongkan cart');
                }

                this.cart = await response.json();

            } catch (error) {

                console.error('Clear Cart Error:', error);

                alert('Gagal mengosongkan keranjang.');
            }
        }

    }
}
</script>

@endsection