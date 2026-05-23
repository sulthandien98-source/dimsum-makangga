@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- ================= STAT CARDS ================= --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="rounded-2xl p-5 bg-blue-700/80">
        <p class="text-xs text-blue-200 font-bold mb-1 uppercase tracking-wide">Total Pesanan</p>
        <p class="text-3xl font-black text-white">{{ $totalOrders }}</p>
    </div>

    <div class="rounded-2xl p-5 bg-green-700/80">
        <p class="text-xs text-green-200 font-bold mb-1 uppercase tracking-wide">Total Revenue</p>
        <p class="text-xl font-black text-white">
            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </p>
    </div>

    <div class="rounded-2xl p-5 bg-orange-600/80">
        <p class="text-xs text-orange-200 font-bold mb-1 uppercase tracking-wide">Pesanan Hari Ini</p>
        <p class="text-3xl font-black text-white">{{ $todayOrders }}</p>
    </div>

    <div class="rounded-2xl p-5 bg-purple-700/80">
        <p class="text-xs text-purple-200 font-bold mb-1 uppercase tracking-wide">Revenue Hari Ini</p>
        <p class="text-xl font-black text-white">
            Rp {{ number_format($todayRevenue, 0, ',', '.') }}
        </p>
    </div>

</div>

{{-- ================= SECONDARY STATS ================= --}}
<div class="grid grid-cols-3 gap-4 mb-8">

    <div class="admin-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-yellow-500/20">⏳</div>
        <div>
            <p class="text-2xl font-black text-white">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-400">Pending</p>
        </div>
    </div>

    <div class="admin-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-orange-500/20">🍽️</div>
        <div>
            <p class="text-2xl font-black text-white">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-400">Produk Aktif</p>
        </div>
    </div>

    <div class="admin-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-red-500/20">⚠️</div>
        <div>
            <p class="text-2xl font-black text-white">{{ $outOfStock }}</p>
            <p class="text-xs text-gray-400">Stok Habis</p>
        </div>
    </div>

</div>

{{-- ================= STATUS + RECENT ORDERS ================= --}}
<div class="grid lg:grid-cols-2 gap-6">

    <div class="admin-card">
        <h2 class="text-white font-black mb-4">📊 Status Pesanan</h2>

        @foreach($statusCounts as $status => $count)
        @php
            $label = \App\Models\Order::STATUSES[$status]['label'] ?? ucfirst($status);
            $color = \App\Models\Order::STATUSES[$status]['color'] ?? 'gray';
            $colorMap = [
                'yellow' => 'bg-yellow-500/20 text-yellow-400',
                'orange' => 'bg-orange-500/20 text-orange-400',
                'blue'   => 'bg-blue-500/20 text-blue-400',
                'green'  => 'bg-green-500/20 text-green-400',
                'gray'   => 'bg-gray-500/20 text-gray-400',
            ];
        @endphp
        <div class="flex justify-between items-center mb-3">
            <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $colorMap[$color] ?? $colorMap['gray'] }}">
                {{ $label }}
            </span>
            <span class="text-white font-bold">{{ $count }}</span>
        </div>
        @endforeach

        @if($statusCounts->isEmpty())
        <p class="text-gray-500 text-sm text-center py-4">Belum ada pesanan</p>
        @endif
    </div>

    <div class="admin-card">
        <h2 class="text-white font-black mb-4">🕒 Pesanan Terbaru</h2>

        @forelse($latestOrders as $o)
        <a href="{{ route('admin.orders.show', $o->id) }}"
           class="flex justify-between border-b border-gray-800 py-2 hover:opacity-80 transition">
            <div>
                <p class="text-white font-bold text-sm">{{ $o->customer_name }}</p>
                <p class="text-gray-500 text-xs">{{ $o->created_at->diffForHumans() }}</p>
            </div>
            <p class="text-orange-400 font-bold text-sm self-center">
                Rp {{ number_format($o->total_price, 0, ',', '.') }}
            </p>
        </a>
        @empty
        <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
        @endforelse

    </div>

</div>

@endsection
