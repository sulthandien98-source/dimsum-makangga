@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-black text-white flex items-center gap-2">
            🧾 Checkout
        </h2>

        <a href="{{ route('menu') }}"
           class="px-4 py-2 rounded-xl bg-[#1f1f1f] border border-[#2a2a2a] text-gray-300 hover:bg-[#2a2a2a] transition">
            ← Kembali
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- ================= LEFT: CART ================= -->
        <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl text-white">

            <h3 class="font-bold text-xl mb-5 flex items-center gap-2">
                🛒 Ringkasan Pesanan
            </h3>

            <div class="space-y-4">

                @foreach($cart as $item)
                    <div class="flex justify-between items-center border-b border-[#262626] pb-3">

                        <div>
                            <p class="font-semibold">
                                {{ $item['name'] }}
                            </p>
                            <p class="text-sm text-gray-400">
                                x{{ $item['qty'] }}
                            </p>
                        </div>

                        <div class="font-bold text-orange-400">
                            Rp {{ number_format($item['price'] * $item['qty'],0,',','.') }}
                        </div>

                    </div>
                @endforeach

            </div>

            <!-- TOTAL -->
            @php
                $total = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
            @endphp

            <div class="mt-6 pt-4 border-t border-[#262626] flex justify-between text-lg font-bold">
                <span>Total</span>
                <span class="text-green-400 text-xl">
                    Rp {{ number_format($total,0,',','.') }}
                </span>
            </div>

        </div>

        <!-- ================= RIGHT: FORM ================= -->
        <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl text-white">

            <h3 class="font-bold text-xl mb-5 flex items-center gap-2">
                📋 Data Pembeli
            </h3>

            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                <!-- NAME -->
                <div class="mb-4">
                    <label class="text-sm text-gray-400">Nama Lengkap</label>
                    <input type="text" name="name"
                        class="w-full mt-1 px-4 py-3 rounded-xl bg-[#0d0d0d] border border-[#262626]
                               focus:ring-2 focus:ring-orange-500 outline-none text-white"
                        placeholder="Masukkan nama..."
                        required>
                </div>

                <!-- PHONE -->
                <div class="mb-4">
                    <label class="text-sm text-gray-400">No HP</label>
                    <input type="text" name="phone"
                        class="w-full mt-1 px-4 py-3 rounded-xl bg-[#0d0d0d] border border-[#262626]
                               focus:ring-2 focus:ring-orange-500 outline-none text-white"
                        placeholder="08xxxxxxxxxx"
                        required>
                </div>

                <!-- ADDRESS -->
                <div class="mb-6">
                    <label class="text-sm text-gray-400">Alamat Lengkap</label>
                    <textarea name="address"
                        class="w-full mt-1 px-4 py-3 rounded-xl bg-[#0d0d0d] border border-[#262626]
                               focus:ring-2 focus:ring-orange-500 outline-none text-white"
                        placeholder="Alamat lengkap..."
                        rows="3"
                        required></textarea>
                </div>

                <!-- INFO -->
                <div class="bg-orange-500/10 border border-orange-500/30 text-orange-300 p-3 rounded-xl mb-5 text-sm">
                    ℹ️ Pastikan data sudah benar sebelum lanjut pembayaran
                </div>

                <!-- BUTTON -->
                <button
                    class="w-full py-3 rounded-xl font-bold text-lg
                           bg-gradient-to-r from-orange-500 to-orange-600
                           hover:opacity-90 transition shadow-lg
                           flex justify-center items-center gap-2">

                    Lanjut ke Pembayaran →
                </button>

            </form>

        </div>

    </div>

</div>

@endsection