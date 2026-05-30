@extends('layouts.app')

@section('content')

@if(empty($cart) || count($cart) === 0)

<div class="max-w-2xl mx-auto">

```
<div class="card-ui p-10 text-center">

    <div class="text-7xl mb-5">
        🛒
    </div>

    <h2 class="text-3xl font-bold mb-3">
        Keranjang Kosong
    </h2>

    <p class="text-gray-500 mb-8">
        Silakan pilih menu terlebih dahulu sebelum melakukan checkout.
    </p>

    <a href="{{ route('menu') }}"
        class="inline-flex items-center px-6 py-3 rounded-xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">

        Kembali ke Menu

    </a>

</div>
```

</div>

@else

@php
$total = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
@endphp

<div class="max-w-6xl mx-auto px-4">

```
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

    <h2 class="text-3xl font-black flex items-center gap-3">
        🧾 Checkout
    </h2>

    <a href="{{ route('menu') }}"
        class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 transition">

        ← Kembali

    </a>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- RINGKASAN PESANAN --}}
    <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">

        <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
            🛒 Ringkasan Pesanan
        </h3>

        <div class="space-y-4">

            @foreach($cart as $item)

            <div class="flex justify-between items-center border-b border-gray-100 pb-4">

                <div>

                    <p class="font-semibold">
                        {{ $item['name'] }}
                    </p>

                    <p class="text-sm text-gray-500">
                        x{{ $item['qty'] }}
                    </p>

                </div>

                <div class="font-bold text-orange-500">

                    Rp {{ number_format($item['price'] * $item['qty'],0,',','.') }}

                </div>

            </div>

            @endforeach

        </div>

        <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">

            <span class="font-bold text-lg">
                Total
            </span>

            <span class="text-2xl font-black text-green-600">

                Rp {{ number_format($total,0,',','.') }}

            </span>

        </div>

    </div>

    {{-- FORM PEMBELI --}}
    <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">

        <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
            📋 Data Pembeli
        </h3>

        @if ($errors->any())

        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4">

            <ul class="list-disc pl-5 text-red-600 text-sm space-y-1">

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form method="POST"
            action="{{ route('checkout.process') }}"
            id="checkout-form">

            @csrf

            {{-- NAMA --}}
            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', auth()->user()->name ?? '') }}"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none"
                    placeholder="Masukkan nama lengkap"
                    required>

            </div>

            {{-- TELEPON --}}
            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Nomor HP
                </label>

                <input
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none"
                    placeholder="08xxxxxxxxxx"
                    required>

            </div>

            {{-- ALAMAT --}}
            <div class="mb-6">

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Alamat Lengkap
                </label>

                <textarea
                    name="address"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none"
                    placeholder="Masukkan alamat lengkap"
                    required>{{ old('address') }}</textarea>

            </div>

            <div class="mb-6 rounded-2xl border border-orange-200 bg-orange-50 p-4 text-sm text-orange-700">

                ℹ️ Pastikan data pesanan dan alamat sudah benar sebelum melanjutkan pembayaran.

            </div>

            <button
                id="checkout-btn"
                type="submit"
                class="w-full py-4 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg shadow-lg hover:opacity-95 transition">

                <span id="checkout-text">

                    Lanjut ke Pembayaran →

                </span>

            </button>

        </form>

    </div>

</div>
```

</div>

<script>

document.addEventListener('DOMContentLoaded', () => {

    const form =
        document.getElementById('checkout-form');

    const button =
        document.getElementById('checkout-btn');

    const text =
        document.getElementById('checkout-text');

    form?.addEventListener('submit', () => {

        button.disabled = true;

        button.classList.add(
            'opacity-75',
            'cursor-not-allowed'
        );

        text.innerHTML =
            'Memproses Pesanan...';

    });

});

</script>

@endif

@endsection
