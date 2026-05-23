@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-black text-white">📦 Semua Pesanan</h1>
        <p class="text-sm text-gray-500">Kelola & verifikasi pembayaran customer</p>
    </div>
    <span class="text-gray-400 text-sm">{{ $orders->total() }} pesanan</span>
</div>

@if(session('success'))
<div x-data="{show:true}" x-init="setTimeout(()=>show=false,4000)" x-show="show"
     class="bg-green-500/20 border border-green-500/50 text-green-400 p-3 rounded-xl mb-5 text-sm flex items-center gap-2">
    ✅ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div x-data="{show:true}" x-init="setTimeout(()=>show=false,4000)" x-show="show"
     class="bg-red-500/20 border border-red-500/50 text-red-400 p-3 rounded-xl mb-5 text-sm flex items-center gap-2">
    ⚠️ {{ session('error') }}
</div>
@endif

{{-- CARD GRID --}}
<div class="space-y-4">

    @forelse($orders as $order)

    {{-- ALPINE COMPONENT PER ROW --}}
    <div x-data="{
            lightbox: false,
            rejectModal: false, rejectReason: '', rejectError: '',
            rejectOrderModal: false, orderRejectReason: '', orderRejectError: '',
            submitReject() {
                if (this.rejectReason.trim().length < 5) { this.rejectError = 'Alasan minimal 5 karakter.'; return; }
                this.rejectError = '';
                this.$refs.rejectForm{{ $order->id }}.submit();
            },
            submitRejectOrder() {
                if (this.orderRejectReason.trim().length < 5) { this.orderRejectError = 'Alasan minimal 5 karakter.'; return; }
                this.orderRejectError = '';
                this.$refs.rejectOrderForm{{ $order->id }}.submit();
            }
         }"
         class="admin-card">

        <div class="flex flex-col md:flex-row md:items-center gap-4">

            {{-- INFO CUSTOMER --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span class="font-black text-white text-base">#{{ $order->id }}</span>
                    <span class="font-bold text-gray-200 truncate">{{ $order->customer_name }}</span>

                    {{-- ORDER STATUS BADGE --}}
                    @php
                        $orderColors = [
                            'yellow' => 'bg-yellow-500/20 text-yellow-400',
                            'orange' => 'bg-orange-500/20 text-orange-400',
                            'blue'   => 'bg-blue-500/20 text-blue-400',
                            'green'  => 'bg-green-500/20 text-green-400',
                            'gray'   => 'bg-gray-500/20 text-gray-400',
                        ];
                        $oCls = $orderColors[$order->status_color] ?? $orderColors['gray'];
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $oCls }}">
                        {{ $order->status_label }}
                    </span>
                </div>

                <p class="text-orange-400 font-black text-lg">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </p>
                <p class="text-gray-500 text-xs mt-0.5">
                    {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>

            {{-- PAYMENT STATUS + PROOF --}}
            <div class="flex flex-col items-start md:items-center gap-2">

                {{-- PAYMENT STATUS BADGE --}}
                @if(!$order->hasPaymentProof())
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-500/20 text-gray-400 border border-gray-500/30">
                        📂 Belum Upload Bukti
                    </span>
                @elseif($order->payment_status === 'paid')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                        ✅ Pembayaran Diterima
                    </span>
                @elseif($order->payment_status === 'rejected')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                        ❌ Pembayaran Ditolak
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                        ⏳ Menunggu Verifikasi
                    </span>
                @endif

                @if($order->isOrderRejected())
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-600/20 text-red-300 border border-red-600/30">
                    🚫 Pesanan Ditolak
                </span>
                @endif

                {{-- THUMBNAIL + PREVIEW BUTTON --}}
                @if($order->hasPaymentProof())
                <button @click="lightbox = true"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#1f1f1f] border border-[#333]
                               text-gray-300 hover:border-orange-500/50 hover:text-orange-400 transition text-xs font-bold">
                    <img src="{{ asset($order->payment_proof) }}"
                         alt="bukti"
                         class="w-8 h-8 rounded object-cover border border-white/10">
                    🔍 Lihat Bukti
                </button>
                @endif

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex flex-col gap-2 min-w-[160px]">

                {{-- VERIFY --}}
                @if($order->hasPaymentProof() && $order->payment_status === 'pending')
                <form method="POST" action="{{ route('admin.orders.verify', $order->id) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Verifikasi pembayaran #{{ $order->id }}?')"
                            class="w-full py-2 px-3 rounded-xl text-xs font-black text-white
                                   bg-gradient-to-r from-green-600 to-green-500
                                   hover:opacity-90 transition flex items-center justify-center gap-1.5">
                        ✅ Verifikasi
                    </button>
                </form>

                {{-- REJECT --}}
                <button @click="rejectModal = true"
                        class="w-full py-2 px-3 rounded-xl text-xs font-black text-white
                               bg-gradient-to-r from-red-700 to-red-600
                               hover:opacity-90 transition flex items-center justify-center gap-1.5">
                    ❌ Tolak
                </button>
                @endif

                {{-- REJECT ORDER BUTTON --}}
                @if(!$order->isOrderRejected() && !$order->isDone())
                <button @click="rejectOrderModal = true"
                        class="w-full py-2 px-3 rounded-xl text-xs font-black text-white
                               bg-gradient-to-r from-red-900 to-red-700
                               hover:opacity-90 transition flex items-center justify-center gap-1.5">
                    🚫 Tolak Pesanan
                </button>
                @endif

                {{-- DETAIL LINK --}}
                <a href="{{ route('admin.orders.show', $order->id) }}"
                   class="w-full text-center py-2 px-3 rounded-xl text-xs font-bold
                          bg-[#1f1f1f] border border-[#333] text-gray-300
                          hover:bg-[#2a2a2a] hover:text-white transition">
                    👁 Detail
                </a>

            </div>
        </div>

        {{-- REJECTION REASON DISPLAY --}}
        @if($order->isPaymentRejected() && $order->rejection_reason)
        <div class="mt-3 bg-red-500/10 border border-red-500/20 rounded-xl p-3">
            <p class="text-red-400 text-xs font-bold mb-0.5">Alasan Penolakan:</p>
            <p class="text-red-300 text-xs">{{ $order->rejection_reason }}</p>
        </div>
        @endif

        @if($order->isPaid() && $order->verified_at)
        <div class="mt-3 bg-green-500/10 border border-green-500/20 rounded-xl p-3 flex items-center gap-2">
            <span class="text-green-400 text-xs">
                ✅ Diverifikasi oleh <strong>{{ $order->verifier?->name ?? 'Admin' }}</strong>
                pada {{ $order->verified_at->format('d M Y H:i') }}
            </span>
        </div>
        @endif

        {{-- ===== LIGHTBOX MODAL ===== --}}
        <div x-show="lightbox"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="lightbox = false"
             @keydown.escape.window="lightbox = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4">
            <div class="relative max-w-2xl w-full" @click.stop>
                <div class="bg-[#161616] border border-[#2a2a2a] rounded-2xl overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-between px-5 py-3 border-b border-[#262626]">
                        <p class="text-white font-bold text-sm">📸 Bukti Pembayaran — Order #{{ $order->id }}</p>
                        <button @click="lightbox = false"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5
                                       hover:bg-white/10 text-gray-400 hover:text-white transition text-lg">
                            ✕
                        </button>
                    </div>
                    @if($order->hasPaymentProof())
                    <img src="{{ asset($order->payment_proof) }}"
                         alt="Bukti Pembayaran #{{ $order->id }}"
                         class="w-full max-h-[70vh] object-contain bg-black/30">
                    <div class="px-5 py-3 border-t border-[#262626] flex items-center justify-between">
                        <p class="text-gray-500 text-xs">
                            Customer: {{ $order->customer_name }}
                            · Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                        @if($order->payment_status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.orders.verify', $order->id) }}">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Verifikasi pembayaran ini?')"
                                        class="px-4 py-1.5 rounded-xl text-xs font-bold text-white
                                               bg-green-600 hover:bg-green-500 transition">
                                    ✅ Verifikasi
                                </button>
                            </form>
                            <button @click="lightbox=false; rejectModal=true"
                                    class="px-4 py-1.5 rounded-xl text-xs font-bold text-white
                                           bg-red-700 hover:bg-red-600 transition">
                                ❌ Tolak
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== REJECT ORDER MODAL ===== --}}
        <div x-show="rejectOrderModal"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.self="rejectOrderModal = false"
             @keydown.escape.window="rejectOrderModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
            <div class="bg-[#161616] border border-red-700/40 rounded-2xl p-6 w-full max-w-md shadow-2xl" @click.stop>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-white font-black text-lg">🚫 Tolak Pesanan</h3>
                    <button @click="rejectOrderModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5
                                   hover:bg-white/10 text-gray-400 hover:text-white transition">✕</button>
                </div>
                <p class="text-gray-500 text-xs mb-4">
                    Order <span class="text-white font-bold">#{{ $order->id }}</span>
                    — {{ $order->customer_name }}
                </p>
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 mb-4">
                    <p class="text-red-400 text-xs font-bold">⚠️ Perhatian</p>
                    <p class="text-red-300 text-xs mt-1">
                        Status pesanan akan menjadi <strong>Ditolak</strong>. Customer akan melihat alasan ini.
                    </p>
                </div>
                <form x-ref="rejectOrderForm{{ $order->id }}"
                      method="POST"
                      action="{{ route('admin.orders.rejectOrder', $order->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                            Alasan <span class="text-red-400">*</span>
                        </label>
                        <textarea x-model="orderRejectReason"
                                  name="order_rejection_reason"
                                  rows="3"
                                  placeholder="Contoh: Stok habis, di luar area pengiriman..."
                                  class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl
                                         px-4 py-3 text-sm outline-none focus:border-red-500/60 resize-none
                                         placeholder:text-gray-600 transition"></textarea>
                        <p x-show="orderRejectError" x-cloak
                           class="text-red-400 text-xs mt-1.5" x-text="orderRejectError"></p>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="rejectOrderModal = false"
                                class="flex-1 py-2.5 rounded-xl border border-[#333] text-gray-400
                                       hover:bg-[#1f1f1f] transition text-sm font-bold">
                            Batal
                        </button>
                        <button type="button" @click="submitRejectOrder()"
                                class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-red-900 to-red-700
                                       text-white font-black text-sm hover:opacity-90 transition">
                            🚫 Tolak Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== REJECT MODAL ===== --}}
        <div x-show="rejectModal"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.self="rejectModal = false"
             @keydown.escape.window="rejectModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
            <div class="bg-[#161616] border border-[#2a2a2a] rounded-2xl p-6 w-full max-w-md shadow-2xl" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-white font-black text-lg">❌ Tolak Pembayaran</h3>
                    <button @click="rejectModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5
                                   hover:bg-white/10 text-gray-400 hover:text-white transition">
                        ✕
                    </button>
                </div>

                <p class="text-gray-400 text-sm mb-4">
                    Masukkan alasan penolakan untuk order
                    <span class="text-white font-bold">#{{ $order->id }}</span>
                    ({{ $order->customer_name }}).
                    Customer akan melihat alasan ini.
                </p>

                <form x-ref="rejectForm{{ $order->id }}"
                      method="POST"
                      action="{{ route('admin.orders.reject', $order->id) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                            Alasan Penolakan <span class="text-red-400">*</span>
                        </label>
                        <textarea x-model="rejectReason"
                                  name="rejection_reason"
                                  rows="3"
                                  placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai / foto buram..."
                                  class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl
                                         px-4 py-3 text-sm outline-none focus:border-red-500/60 resize-none
                                         placeholder:text-gray-600 transition"></textarea>
                        <p x-show="rejectError" x-cloak
                           class="text-red-400 text-xs mt-1.5" x-text="rejectError"></p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button"
                                @click="rejectModal = false"
                                class="flex-1 py-2.5 rounded-xl border border-[#333] text-gray-400
                                       hover:bg-[#1f1f1f] transition text-sm font-bold">
                            Batal
                        </button>
                        <button type="button"
                                @click="submitReject()"
                                class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-red-700 to-red-600
                                       text-white font-black text-sm hover:opacity-90 transition">
                            ❌ Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @empty

    <div class="admin-card text-center py-16">
        <p class="text-4xl mb-3">📭</p>
        <p class="text-gray-500">Belum ada pesanan</p>
    </div>

    @endforelse

</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $orders->links() }}
</div>

@endsection
