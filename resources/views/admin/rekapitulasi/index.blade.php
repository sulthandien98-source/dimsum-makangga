@extends('layouts.admin')

@section('title', 'Rekapitulasi')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-4xl font-black text-white mb-2">
            📊 Rekapitulasi Penjualan
        </h1>

        <p class="text-gray-400">
            Statistik penjualan harian, mingguan, dan bulanan
        </p>
    </div>

    <!-- CARDS -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">

        <!-- HARIAN -->
        <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-gray-400 font-semibold">
                    Penjualan Hari Ini
                </h2>

                <span class="text-3xl">📅</span>
            </div>

            <h3 class="text-3xl font-black text-orange-400 mb-2">
                Rp {{ number_format($dailySales) }}
            </h3>

            <p class="text-gray-500">
                {{ $dailyOrders }} pesanan
            </p>

        </div>

        <!-- MINGGUAN -->
        <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-gray-400 font-semibold">
                    Penjualan Mingguan
                </h2>

                <span class="text-3xl">📈</span>
            </div>

            <h3 class="text-3xl font-black text-blue-400 mb-2">
                Rp {{ number_format($weeklySales) }}
            </h3>

            <p class="text-gray-500">
                {{ $weeklyOrders }} pesanan
            </p>

        </div>

        <!-- BULANAN -->
        <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-gray-400 font-semibold">
                    Penjualan Bulanan
                </h2>

                <span class="text-3xl">💰</span>
            </div>

            <h3 class="text-3xl font-black text-green-400 mb-2">
                Rp {{ number_format($monthlySales) }}
            </h3>

            <p class="text-gray-500">
                {{ $monthlyOrders }} pesanan
            </p>

        </div>

    </div>

    <!-- GRAFIK -->
    <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl mb-8">

        <h2 class="text-2xl font-bold text-white mb-6">
            Grafik Penjualan 7 Hari Terakhir
        </h2>

        <div class="space-y-5">

            @foreach($chartData as $data)

                <div>

                    <div class="flex justify-between text-sm mb-2">

                        <span class="text-gray-400">
                            {{ $data['date'] }}
                        </span>

                        <span class="text-white font-bold">
                            Rp {{ number_format($data['sales']) }}
                        </span>

                    </div>

                    <div class="w-full bg-[#262626] rounded-full h-4 overflow-hidden">

                        <div
                            class="bg-orange-500 h-4 rounded-full transition-all duration-500"
                            style="width: {{ $monthlySales > 0 ? ($data['sales'] / $monthlySales) * 100 : 0 }}%">
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

    <!-- TRANSAKSI -->
    <div class="bg-[#161616] border border-[#262626] rounded-2xl p-6 shadow-xl">

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-2xl font-bold text-white">
                Transaksi Terbaru
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b border-[#262626] text-gray-400">

                        <th class="text-left py-3">Customer</th>
                        <th class="text-left py-3">Total</th>
                        <th class="text-left py-3">Status</th>
                        <th class="text-left py-3">Tanggal</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($recentOrders as $order)

                        <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition">

                            <td class="py-4 text-white font-semibold">
                                {{ $order->customer_name }}
                            </td>

                            <td class="py-4 text-orange-400 font-bold">
                                Rp {{ number_format($order->total_price) }}
                            </td>

                            <td class="py-4">

                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($order->status == 'selesai')
                                        bg-green-500/20 text-green-400
                                    @elseif($order->status == 'diproses')
                                        bg-blue-500/20 text-blue-400
                                    @else
                                        bg-yellow-500/20 text-yellow-400
                                    @endif">

                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}

                                </span>

                            </td>

                            <td class="py-4 text-gray-400">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-10 text-gray-500">
                                Belum ada transaksi
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- EXPORT BUTTON -->
    <div class="flex gap-4 mt-8">

        <!-- PDF -->
        <a href="{{ route('admin.rekapitulasi.pdf') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-xl font-bold shadow-lg transition">

            📄 Export PDF

        </a>

        <!-- EXCEL -->
        <a href="{{ route('admin.rekapitulasi.excel') }}"
           class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl font-bold shadow-lg transition">

            📊 Export Excel

        </a>

    </div>

</div>

@endsection