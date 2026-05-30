@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- HEADER --}}
    <div class="text-center mb-8">

        <div class="text-6xl mb-4 animate-bounce">
            ⏳
        </div>

        <h1 class="text-3xl md:text-4xl font-black text-gray-900">
            Status Pesanan
        </h1>

        <p class="text-gray-500 mt-2">
            Pantau perkembangan pesanan Anda secara realtime.
        </p>

    </div>

    {{-- PAYMENT STATUS --}}
    @if($order->hasPaymentProof())

        <div
            class="bg-green-50 border border-green-200 rounded-3xl p-5 mb-6 shadow-sm"
        >

            <div class="flex items-center gap-4">

                <div class="text-4xl">
                    ✅
                </div>

                <div>

                    <h3
                        class="font-bold text-green-700"
                    >
                        Bukti Pembayaran Berhasil Dikirim
                    </h3>

                    <p class="text-green-600 text-sm">
                        Admin sedang melakukan verifikasi pembayaran.
                    </p>

                </div>

            </div>

        </div>

    @else

        <div
            class="bg-yellow-50 border border-yellow-200 rounded-3xl p-5 mb-6 shadow-sm"
        >

            <div class="flex items-center gap-4 mb-4">

                <div class="text-4xl">
                    ⚠️
                </div>

                <div>

                    <h3
                        class="font-bold text-yellow-700"
                    >
                        Bukti Pembayaran Belum Diupload
                    </h3>

                    <p class="text-yellow-600 text-sm">
                        Upload bukti transfer agar pesanan segera diproses.
                    </p>

                </div>

            </div>

            <a
                href="{{ route('payment.proof',$order->id) }}"
                class="block text-center bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-2xl font-bold transition"
            >
                📸 Upload Bukti Pembayaran
            </a>

        </div>

    @endif

    {{-- REJECTED --}}
    @if($order->isOrderRejected())

        <div
            class="bg-red-50 border border-red-200 rounded-3xl p-5 mb-6"
        >

            <div class="flex items-center gap-4 mb-3">

                <div class="text-4xl">
                    🚫
                </div>

                <div>

                    <h3
                        class="font-black text-red-700"
                    >
                        Pesanan Ditolak
                    </h3>

                    <p class="text-red-600 text-sm">
                        Pesanan tidak dapat diproses.
                    </p>

                </div>

            </div>

            @if($order->rejection_reason)

                <div
                    class="bg-white border border-red-200 rounded-2xl p-4 text-red-600"
                >
                    {{ $order->rejection_reason }}
                </div>

            @endif

        </div>

    @endif

    {{-- ORDER CARD --}}
    <div
        class="bg-white border border-gray-100 rounded-3xl shadow-lg p-6 mb-6"
    >

        @php

            $colorMap = [
                'yellow' => 'bg-yellow-100 text-yellow-700',
                'orange' => 'bg-orange-100 text-orange-700',
                'blue'   => 'bg-blue-100 text-blue-700',
                'green'  => 'bg-green-100 text-green-700',
                'red'    => 'bg-red-100 text-red-700',
                'gray'   => 'bg-gray-100 text-gray-700',
            ];

            $badgeClass =
                $colorMap[$order->status_color]
                ?? $colorMap['gray'];

        @endphp

        <div
            class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6"
        >

            <div>

                <p class="text-gray-500 text-sm">
                    Nomor Pesanan
                </p>

                <h2 class="font-black text-2xl">
                    #{{ $order->id }}
                </h2>

            </div>

            <span
                class="px-4 py-2 rounded-full text-sm font-bold {{ $badgeClass }}"
            >
                {{ $order->status_label }}
            </span>

        </div>

        <div class="grid md:grid-cols-2 gap-4">

            <div
                class="bg-gray-50 rounded-2xl p-4"
            >

                <p class="text-gray-500 text-sm mb-1">
                    Nama Pemesan
                </p>

                <h3 class="font-bold">
                    {{ $order->customer_name }}
                </h3>

            </div>

            <div
                class="bg-orange-50 rounded-2xl p-4"
            >

                <p class="text-orange-500 text-sm mb-1">
                    Total Pembayaran
                </p>

                <h3
                    class="font-black text-2xl text-orange-600"
                >
                    Rp {{ number_format($order->total_price,0,',','.') }}
                </h3>

            </div>

        </div>

        <div
            class="mt-6 pt-4 border-t border-gray-100"
        >

            <p class="text-center text-sm text-gray-500">
                Dibuat pada
                {{ $order->created_at->format('d M Y H:i') }}
            </p>

        </div>

    </div>

    {{-- TIMELINE --}}
    <div
        class="bg-white border border-gray-100 rounded-3xl shadow-lg p-6 mb-6"
    >

        <h3
            class="font-bold text-lg mb-5"
        >
            Progress Pesanan
        </h3>

        <div class="space-y-4">

            <div class="flex items-center gap-4">

                <div
                    class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center"
                >
                    ✓
                </div>

                <div>

                    <p class="font-semibold">
                        Pesanan Dibuat
                    </p>

                    <p class="text-sm text-gray-500">
                        Pesanan berhasil masuk ke sistem.
                    </p>

                </div>

            </div>

            <div class="flex items-center gap-4">

                <div
                    class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center"
                >
                    ⏳
                </div>

                <div>

                    <p class="font-semibold">
                        Menunggu Verifikasi
                    </p>

                    <p class="text-sm text-gray-500">
                        Admin sedang memeriksa pembayaran.
                    </p>

                </div>

            </div>

            <div class="flex items-center gap-4 opacity-50">

                <div
                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center"
                >
                    🚚
                </div>

                <div>

                    <p class="font-semibold">
                        Diproses & Dikirim
                    </p>

                    <p class="text-sm text-gray-500">
                        Pesanan akan dikirim setelah verifikasi.
                    </p>

                </div>

            </div>

        </div>

    </div>

    {{-- ACTIONS --}}
    <div
        class="grid sm:grid-cols-2 gap-4"
    >

        <a
            href="{{ route('orders') }}"
            class="text-center py-4 rounded-2xl border border-gray-200 hover:bg-gray-50 transition font-semibold"
        >
            Daftar Pesanan
        </a>

        <a
            href="{{ route('menu') }}"
            class="text-center py-4 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition"
        >
            Pesan Lagi
        </a>

    </div>

</div>

{{-- AUTO REFRESH --}}
<script>

setTimeout(() => {

    location.reload();

}, 30000);

</script>

@endsection