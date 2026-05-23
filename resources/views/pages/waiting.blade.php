@extends('layouts.app')

@section('title', 'Menunggu Konfirmasi')

@section('content')

<div class="max-w-xl mx-auto px-4">

    {{-- STATUS HEADER --}}
    <div class="text-center mb-6">
        <div class="text-5xl mb-4">⏳</div>
        <h1 class="text-2xl font-black text-white mb-2">
            Pesanan Diterima
        </h1>
        <p class="text-amber-200/60 text-sm">
            Pesanan <span class="text-orange-400 font-bold">#{{ $order->id }}</span> sedang diproses
        </p>
    </div>

    {{-- PAYMENT PROOF STATUS --}}
    @if($order->hasPaymentProof())
    <div class="bg-green-500/10 border border-green-500/30 rounded-2xl p-4 mb-5 flex items-center gap-3">
        <span class="text-2xl">✅</span>
        <div>
            <p class="text-green-400 font-bold text-sm">Bukti Pembayaran Terkirim</p>
            <p class="text-green-300/60 text-xs">Menunggu verifikasi admin</p>
        </div>
    </div>
    @else
    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-4 mb-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="text-2xl">⚠️</span>
            <div>
                <p class="text-yellow-400 font-bold text-sm">Belum Upload Bukti Pembayaran</p>
                <p class="text-yellow-300/60 text-xs">Upload sekarang agar pesanan segera diproses</p>
            </div>
        </div>
        <a href="{{ route('payment.proof', $order->id) }}"
           class="block w-full text-center py-2.5 bg-orange-500 hover:bg-orange-600 transition rounded-xl font-bold text-white text-sm">
            📸 Upload Bukti Pembayaran
        </a>
    </div>
    @endif

    {{-- REJECTED BANNER --}}
    @if($order->isOrderRejected())
    <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 mb-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-2xl">🚫</span>
            <p class="text-red-400 font-black text-base">Pesanan Ditolak</p>
        </div>
        @if($order->rejection_reason)
        <p class="text-red-300 text-sm mb-2">{{ $order->rejection_reason }}</p>
        @endif
        @if($order->rejected_at)
        <p class="text-red-400/50 text-xs">
            Ditolak pada {{ $order->rejected_at->format('d M Y H:i') }}
        </p>
        @endif
    </div>
    @endif

    {{-- ORDER DETAIL CARD --}}
    <div class="card mb-5">

        <div class="flex justify-between items-center mb-4">
            <span class="text-amber-200/60 text-sm">Status</span>
            @php
                $colorMap = [
                    'yellow' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                    'orange' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                    'blue'   => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                    'green'  => 'bg-green-500/20 text-green-400 border-green-500/30',
                    'red'    => 'bg-red-500/20 text-red-400 border-red-500/30',
                    'gray'   => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                ];
                $cls = $colorMap[$order->status_color] ?? $colorMap['gray'];
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $cls }}">
                {{ $order->status_label }}
            </span>
        </div>

        <div class="flex justify-between items-center mb-3">
            <span class="text-amber-200/60 text-sm">Nama</span>
            <span class="text-white font-semibold text-sm">{{ $order->customer_name }}</span>
        </div>

        <div class="flex justify-between items-center mb-3">
            <span class="text-amber-200/60 text-sm">Total</span>
            <span class="text-orange-400 font-bold">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </span>
        </div>

        <div class="border-t border-white/10 pt-3 mt-1">
            <p class="text-amber-200/30 text-xs text-center">
                Dibuat: {{ $order->created_at->format('d M Y H:i') }}
            </p>
        </div>

    </div>

    {{-- CTA --}}
    <a href="{{ route('orders') }}"
       class="block w-full text-center py-3 bg-white/5 border border-white/10 hover:bg-white/10 transition rounded-xl font-bold text-amber-100 text-sm">
        Lihat Semua Pesanan
    </a>

</div>

@endsection
