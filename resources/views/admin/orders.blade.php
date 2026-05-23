@extends('layouts.admin')

@section('title', 'Kelola Orders')

@section('content')

<div class="space-y-4">

    @forelse($orders as $o)
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5
                shadow-xl flex justify-between items-center">

        <div>
            <p class="font-bold text-white">{{ $o->customer_name }}</p>
            <p class="text-sm text-gray-300">📞 {{ $o->phone }}</p>
            @if($o->address)
                <p class="text-sm text-gray-400">📍 {{ $o->address }}</p>
            @endif
            <p class="text-xs text-gray-500 mt-1">
                {{ $o->created_at->format('d M Y H:i') }}
            </p>
        </div>

        <div class="text-right">
            <p class="text-red-400 font-bold text-xl">
                Rp {{ number_format($o->total_price, 0, ',', '.') }}
            </p>
            <span class="bg-green-500/80 text-white text-xs px-3 py-1 rounded-full mt-1 inline-block">
                Paid
            </span>
        </div>

    </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">📦</p>
            <p>Belum ada order masuk</p>
        </div>
    @endforelse

</div>

@endsection
