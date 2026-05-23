@extends('layouts.app')

@section('content')

<div x-data="{ loading: false }"
     class="max-w-3xl mx-auto">

    <h1 class="text-3xl font-black mb-6 flex items-center gap-2 text-white">
        💳 Pembayaran
    </h1>

    <div class="bg-[#161616] border border-[#262626] rounded-2xl shadow-xl p-6">

        <form method="POST"
              action="{{ route('payment.auto') }}"
              @submit="loading = true">

            @csrf

            @php
                $total = collect(session('cart', []))
                    ->sum(fn($i) => $i['price'] * $i['qty']);
            @endphp

            <!-- RINGKASAN -->
            <div class="mb-6">

                <h2 class="font-bold text-lg mb-3 text-gray-200">
                    🧾 Ringkasan
                </h2>

                @foreach(session('cart', []) as $item)

                    <div class="flex justify-between text-sm mb-2 text-gray-400">

                        <span>
                            {{ $item['name'] }}
                            x{{ $item['qty'] }}
                        </span>

                        <span>
                            Rp {{ number_format($item['price'] * $item['qty']) }}
                        </span>

                    </div>

                @endforeach

                <div class="border-t border-[#262626] mt-3 pt-3 flex justify-between font-bold text-white">

                    <span>Total</span>

                    <span class="text-orange-400">
                        Rp {{ number_format($total) }}
                    </span>

                </div>

            </div>

            <!-- PAYMENT -->
            <div class="mb-6">

                <h2 class="font-bold text-lg mb-4 text-gray-200">
                    Metode Pembayaran
                </h2>

                <div class="p-4 rounded-xl border border-orange-500 bg-orange-500/10">

                    <div class="flex items-center justify-between">

                        <span>
                            🏦 Transfer BCA
                        </span>

                        <span>✓</span>

                    </div>

                    <div class="mt-4 bg-[#1f1f1f] p-4 rounded-xl text-gray-200">

                        <p>
                            No Rek:
                            <b>0711982697</b>
                        </p>

                        <p>
                            Atas Nama:
                            <b>Endang Triningrum Rizkaw</b>
                        </p>

                    </div>

                </div>

            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full py-3 bg-orange-500 hover:bg-orange-600 transition rounded-xl font-bold text-white">

                <span x-show="!loading">
                    Menunggu Konfirmasi Pembayaran
                </span>

                <span x-show="loading">
                    Memproses...
                </span>

            </button>

        </form>

    </div>
</div>

@endsection