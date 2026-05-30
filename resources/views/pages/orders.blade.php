@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')

<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-black mb-6 flex items-center gap-2 text-white">
        📦 Pesanan Saya
    </h1>

    @if(session('success'))
    <div
        x-data="{show:true}"
        x-init="setTimeout(() => show = false, 3500)"
        x-show="show"
        class="bg-green-500/20 border border-green-500/50 text-green-400 p-4 rounded-xl mb-6 flex justify-between items-center text-sm">

        <span>✅ {{ session('success') }}</span>

        <button
            @click="show=false"
            class="text-green-400/60 hover:text-green-400">
            ✕
        </button>

    </div>
    @endif

    <div class="space-y-4">

        @forelse($orders as $o)

        <div class="card">

            {{-- HEADER --}}
            <div class="flex items-start justify-between mb-3 gap-2 flex-wrap">

                <div>

                    <div class="flex items-center gap-2 flex-wrap">

                        <span class="text-white font-black text-base">
                            #{{ $o->id }}
                        </span>

                        <span class="text-amber-100/80 font-semibold">
                            {{ $o->customer_name }}
                        </span>

                    </div>

                    <p class="text-amber-200/40 text-xs mt-0.5">
                        {{ $o->created_at->format('d M Y H:i') }}
                    </p>

                </div>

                <div class="flex flex-col items-end gap-1.5">

                    {{-- ORDER STATUS --}}
                    @php
                        $orderBadge = [
                            'pending'             => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                            'menunggu_konfirmasi' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                            'diproses'            => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            'selesai'             => 'bg-green-500/20 text-green-400 border-green-500/30',
                            'rejected'            => 'bg-red-500/20 text-red-400 border-red-500/30',
                        ];

                        $oBadge = $orderBadge[$o->status]
                            ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                    @endphp

                    <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $oBadge }}">
                        {{ $o->status_label }}
                    </span>

                    {{-- PAYMENT STATUS --}}
                    @if(!$o->hasPaymentProof())

                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-500/15 text-gray-500 border border-gray-500/20">
                            📂 Belum Upload Bukti
                        </span>

                    @elseif($o->payment_status === 'paid')

                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                            ✅ Pembayaran Diterima
                        </span>

                    @elseif($o->payment_status === 'rejected')

                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                            ❌ Pembayaran Ditolak
                        </span>

                    @else

                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-500/15 text-yellow-400 border border-yellow-500/25">
                            ⏳ Menunggu Verifikasi
                        </span>

                    @endif

                </div>

            </div>

            {{-- ITEMS --}}
            @if($o->items && count($o->items))

            <div class="border-t border-white/5 py-3 text-sm text-amber-200/60 space-y-1">

                @foreach($o->items as $item)

                <div class="flex justify-between">

                    <span>
                        {{ $item->product->name ?? 'Produk dihapus' }}
                        ×{{ $item->quantity }}
                    </span>

                    <span>
                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </span>

                </div>

                @endforeach

            </div>

            @endif

            {{-- TOTAL --}}
            <div class="flex justify-between items-center pt-3 border-t border-white/5">

                <span class="text-amber-200/50 text-sm">
                    Total
                </span>

                <span class="text-orange-400 font-black text-xl">
                    Rp {{ number_format($o->total_price, 0, ',', '.') }}
                </span>

            </div>

            {{-- PAYMENT REJECTED --}}
            @if($o->isPaymentRejected() && $o->rejection_reason)

            <div class="mt-3 bg-red-500/10 border border-red-500/20 rounded-xl p-4">

                <p class="text-red-400 text-xs font-bold mb-1 uppercase tracking-wide">
                    ⚠️ Alasan Penolakan Pembayaran
                </p>

                <p class="text-red-300 text-sm">
                    {{ $o->rejection_reason }}
                </p>

                <p class="text-red-400/50 text-xs mt-2">
                    Silakan upload ulang bukti pembayaran yang benar.
                </p>

            </div>

            @endif

            {{-- ORDER REJECTED --}}
            @if($o->isOrderRejected())

            <div class="mt-3 bg-red-500/10 border border-red-500/20 rounded-xl p-4">

                <p class="text-red-400 text-xs font-bold mb-1 uppercase tracking-wide">
                    🚫 Pesanan Anda Ditolak
                </p>

                @if($o->rejection_reason)
                <p class="text-red-300 text-sm">
                    {{ $o->rejection_reason }}
                </p>
                @endif

                @if($o->rejected_at)
                <p class="text-red-400/50 text-xs mt-2">
                    Ditolak pada {{ $o->rejected_at->format('d M Y H:i') }}
                </p>
                @endif

                <p class="text-red-400/60 text-xs mt-1">
                    Silakan hubungi kami jika ada pertanyaan.
                </p>

            </div>

            @endif

            {{-- UPLOAD PAYMENT PROOF --}}
            @if(!$o->isOrderRejected() && (!$o->hasPaymentProof() || $o->isPaymentRejected()))

            <div class="mt-3">

                <a
                    href="{{ route('payment.proof', $o->id) }}"
                    class="block w-full text-center py-2.5 bg-orange-500 hover:bg-orange-600 transition rounded-xl font-bold text-white text-sm">

                    📸 {{ $o->isPaymentRejected() ? 'Upload Ulang Bukti' : 'Upload Bukti Pembayaran' }}

                </a>

            </div>

            @endif

            {{-- LIHAT STATUS --}}
            @if(!$o->isDone())

            <div class="mt-3">

                <a
                    href="{{ route('order.waiting', $o->id) }}"
                    class="block w-full text-center py-2.5 border border-orange-500/30 text-orange-400 rounded-xl hover:bg-orange-500/10 transition font-bold text-sm">

                    🔍 Lihat Status Pesanan

                </a>

            </div>

            @endif

        </div>

        @empty

        <div class="card text-center py-16">

            <p class="text-4xl mb-3">
                😢
            </p>

            <p class="text-amber-200/50 mb-4">
                Belum ada pesanan
            </p>

            <a
                href="{{ route('menu') }}"
                class="bg-orange-500 text-white px-6 py-2.5 rounded-xl hover:bg-orange-600 transition font-bold">

                Kembali ke Menu

            </a>

        </div>

        @endforelse

    </div>

</div>

@endsection