@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')

<div x-data="{
    lightbox: false,
    rejectModal: false, rejectReason: '', rejectError: '',
    rejectOrderModal: false, orderRejectReason: '', orderRejectError: '',
    submitReject() {
        if (this.rejectReason.trim().length < 5) { this.rejectError = 'Alasan minimal 5 karakter.'; return; }
        this.rejectError = '';
        this.$refs.rejectFormShow.submit();
    },
    submitRejectOrder() {
        if (this.orderRejectReason.trim().length < 5) { this.orderRejectError = 'Alasan minimal 5 karakter.'; return; }
        this.orderRejectError = '';
        this.$refs.rejectOrderFormShow.submit();
    }
}">

<div class="grid lg:grid-cols-3 gap-6">

    {{-- ======================== LEFT ======================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- INVOICE HEADER --}}
        <div class="admin-card flex justify-between items-start flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-black text-white">🧾 Invoice #{{ $order->id }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex flex-col items-end gap-2">
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
                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $oCls }}">
                    {{ $order->status_label }}
                </span>

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
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-600/25 text-red-300 border border-red-600/40">
                    🚫 Pesanan Ditolak
                </span>
                @endif
            </div>
        </div>

        {{-- FLASH --}}
        @if(session('success'))
        <div x-data="{show:true}" x-init="setTimeout(()=>show=false,4000)" x-show="show"
             class="bg-green-500/20 border border-green-500/50 text-green-400 p-3 rounded-xl text-sm">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div x-data="{show:true}" x-init="setTimeout(()=>show=false,4000)" x-show="show"
             class="bg-red-500/20 border border-red-500/50 text-red-400 p-3 rounded-xl text-sm">
            ⚠️ {{ session('error') }}
        </div>
        @endif

        {{-- CUSTOMER INFO --}}
        <div class="admin-card">
            <h2 class="text-xs text-gray-400 uppercase mb-3 font-bold tracking-wide">Informasi Customer</h2>
            <p class="text-lg font-bold text-white">{{ $order->customer_name }}</p>
            <p class="text-sm text-gray-400 mt-1">📞 {{ $order->phone }}</p>
            <p class="text-sm text-gray-500 mt-1">📍 {{ $order->address }}</p>
            @if($order->user)
            <p class="text-xs text-gray-600 mt-2">Akun: {{ $order->user->email }}</p>
            @endif
        </div>

        {{-- ORDER ITEMS --}}
        <div class="admin-card">
            <h2 class="text-xs text-gray-400 uppercase mb-4 font-bold tracking-wide">Detail Pesanan</h2>
            @foreach($order->items as $item)
            <div class="flex justify-between border-b border-[#1f1f1f] py-3">
                <div>
                    <p class="font-semibold text-white">{{ $item->product->name ?? 'Produk dihapus' }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                    </p>
                </div>
                <p class="text-orange-400 font-bold">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
            <div class="flex justify-between mt-4 pt-3 border-t border-[#1f1f1f]">
                <span class="text-gray-300 font-bold">Total</span>
                <span class="text-orange-400 font-black text-xl">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- BUKTI PEMBAYARAN --}}
        <div class="admin-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs text-gray-400 uppercase font-bold tracking-wide">Bukti Pembayaran</h2>
                @if(!$order->hasPaymentProof())
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-500/20 text-gray-400 border border-gray-500/30">
                        📂 Belum Upload
                    </span>
                @elseif($order->payment_status === 'paid')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                        ✅ Terverifikasi
                    </span>
                @elseif($order->payment_status === 'rejected')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                        ❌ Ditolak
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                        ⏳ Menunggu Verifikasi
                    </span>
                @endif
            </div>

            @if($order->hasPaymentProof())
                <div class="relative rounded-xl overflow-hidden bg-black/30 border border-white/10 cursor-zoom-in group"
                     @click="lightbox = true">
                    <img src="{{ asset($order->payment_proof) }}"
                         alt="Bukti Pembayaran"
                         class="w-full max-h-72 object-contain transition-transform duration-300 group-hover:scale-[1.02]">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                        <span class="bg-black/80 text-white px-4 py-2 rounded-xl text-sm font-bold backdrop-blur-sm">
                            🔍 Klik untuk perbesar
                        </span>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-2 text-center">
                    Diupload: {{ $order->updated_at->format('d M Y H:i') }}
                </p>

                {{-- REJECTION REASON --}}
                @if($order->isPaymentRejected() && $order->rejection_reason)
                <div class="mt-4 bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                    <p class="text-red-400 text-xs font-bold mb-1 uppercase tracking-wide">Alasan Penolakan</p>
                    <p class="text-red-300 text-sm">{{ $order->rejection_reason }}</p>
                    @if($order->verified_at)
                    <p class="text-red-400/50 text-xs mt-2">
                        Ditolak pada {{ $order->verified_at->format('d M Y H:i') }}
                        oleh {{ $order->verifier?->name ?? 'Admin' }}
                    </p>
                    @endif
                </div>
                @endif

                @if($order->isPaid() && $order->verified_at)
                <div class="mt-4 bg-green-500/10 border border-green-500/20 rounded-xl p-4">
                    <p class="text-green-400 text-sm">
                        ✅ Diverifikasi oleh <strong>{{ $order->verifier?->name ?? 'Admin' }}</strong>
                        pada {{ $order->verified_at->format('d M Y H:i') }}
                    </p>
                </div>
                @endif

            @else
                <div class="bg-[#1a1a1a] border border-dashed border-gray-700 rounded-xl p-10 text-center">
                    <div class="text-4xl mb-3">🖼️</div>
                    <p class="text-gray-400 text-sm font-bold mb-1">Belum ada bukti pembayaran</p>
                    <p class="text-gray-600 text-xs">Customer belum mengupload bukti transfer</p>
                </div>
            @endif
        </div>

    </div>

    {{-- ======================== RIGHT ======================== --}}
    <div class="space-y-6">

        {{-- VERIFIKASI PEMBAYARAN --}}
        @if($order->hasPaymentProof() && $order->payment_status === 'pending')
        <div class="admin-card border border-orange-500/20">
            <h2 class="text-xs text-gray-400 uppercase mb-4 font-bold tracking-wide">
                🔐 Verifikasi Pembayaran
            </h2>

            <form method="POST" action="{{ route('admin.orders.verify', $order->id) }}" class="mb-3">
                @csrf
                <button type="submit"
                        onclick="return confirm('Verifikasi pembayaran order #{{ $order->id }}? Status akan berubah ke Diproses.')"
                        class="w-full py-3 rounded-xl font-black text-white text-sm
                               bg-gradient-to-r from-green-700 to-green-500 hover:opacity-90 transition
                               flex items-center justify-center gap-2">
                    ✅ Verifikasi Pembayaran
                </button>
            </form>

            <button @click="rejectModal = true"
                    class="w-full py-3 rounded-xl font-black text-white text-sm
                           bg-gradient-to-r from-red-800 to-red-600 hover:opacity-90 transition
                           flex items-center justify-center gap-2">
                ❌ Tolak Pembayaran
            </button>
        </div>
        @endif

        {{-- TOLAK PESANAN --}}
        @if(!$order->isOrderRejected() && !$order->isDone())
        <div class="admin-card border border-red-500/20">
            <h2 class="text-xs text-gray-400 uppercase mb-4 font-bold tracking-wide">
                🚫 Tolak Pesanan
            </h2>
            <p class="text-gray-500 text-xs mb-3">
                Tolak seluruh pesanan ini. Customer akan melihat alasan penolakan.
            </p>
            <button @click="rejectOrderModal = true"
                    class="w-full py-3 rounded-xl font-black text-white text-sm
                           bg-gradient-to-r from-red-900 to-red-700 hover:opacity-90 transition
                           flex items-center justify-center gap-2">
                🚫 Tolak Pesanan
            </button>
        </div>
        @endif

        @if($order->isOrderRejected())
        <div class="admin-card border border-red-500/30 bg-red-500/5">
            <p class="text-xs text-red-400 uppercase font-bold mb-2 tracking-wide">🚫 Pesanan Ditolak</p>
            <p class="text-red-300 text-sm mb-2">{{ $order->rejection_reason }}</p>
            @if($order->rejected_at)
            <p class="text-red-400/50 text-xs">
                Ditolak oleh {{ $order->rejecter?->name ?? 'Admin' }}
                pada {{ $order->rejected_at->format('d M Y H:i') }}
            </p>
            @endif
        </div>
        @endif

        {{-- UPDATE STATUS --}}
        <div class="admin-card">
            <h2 class="text-xs text-gray-400 uppercase mb-3 font-bold tracking-wide">Update Status Order</h2>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                @csrf
                @method('PUT')
                <select name="status"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white
                           rounded-xl px-3 py-2 mb-4 focus:border-orange-500 outline-none text-sm">
                    @foreach(\App\Models\Order::STATUSES as $value => $info)
                    <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>
                        {{ $info['label'] }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="w-full py-2 rounded-xl font-bold text-white text-sm
                           bg-gradient-to-r from-orange-500 to-orange-600 hover:opacity-90 transition">
                    💾 Update Status
                </button>
            </form>
        </div>

        {{-- BACK --}}
        <div class="admin-card">
            <a href="{{ route('admin.orders.index') }}"
               class="block text-center py-2 border border-gray-700 rounded-xl
                      text-gray-300 hover:bg-[#1f1f1f] transition text-sm">
                ← Kembali ke Daftar
            </a>
        </div>

    </div>
</div>

{{-- ===== LIGHTBOX MODAL ===== --}}
@if($order->hasPaymentProof())
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
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/92 p-4">
    <div class="relative max-w-3xl w-full" @click.stop>
        <div class="bg-[#161616] border border-[#2a2a2a] rounded-2xl overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-5 py-3 border-b border-[#262626]">
                <p class="text-white font-bold text-sm">📸 Bukti Pembayaran — Order #{{ $order->id }}</p>
                <button @click="lightbox = false"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5
                               hover:bg-white/10 text-gray-400 hover:text-white transition text-lg">
                    ✕
                </button>
            </div>
            <img src="{{ asset($order->payment_proof) }}"
                 alt="Bukti Pembayaran"
                 class="w-full max-h-[75vh] object-contain bg-black/40">
            <div class="px-5 py-3 border-t border-[#262626]">
                <p class="text-gray-500 text-xs text-center">
                    {{ $order->customer_name }} · Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    · {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endif

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
                           hover:bg-white/10 text-gray-400 hover:text-white transition">✕</button>
        </div>
        <p class="text-gray-400 text-sm mb-4">
            Masukkan alasan penolakan untuk order
            <span class="text-white font-bold">#{{ $order->id }}</span>.
            Customer akan melihat alasan ini.
        </p>
        <form x-ref="rejectFormShow"
              method="POST"
              action="{{ route('admin.orders.reject', $order->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                    Alasan <span class="text-red-400">*</span>
                </label>
                <textarea x-model="rejectReason"
                          name="rejection_reason"
                          rows="3"
                          placeholder="Contoh: Bukti tidak jelas, nominal tidak sesuai, foto buram..."
                          class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl
                                 px-4 py-3 text-sm outline-none focus:border-red-500/60 resize-none
                                 placeholder:text-gray-600 transition"></textarea>
                <p x-show="rejectError" x-cloak class="text-red-400 text-xs mt-1.5" x-text="rejectError"></p>
                @error('rejection_reason')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="button" @click="rejectModal = false"
                        class="flex-1 py-2.5 rounded-xl border border-[#333] text-gray-400
                               hover:bg-[#1f1f1f] transition text-sm font-bold">
                    Batal
                </button>
                <button type="button" @click="submitReject()"
                        class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-red-800 to-red-600
                               text-white font-black text-sm hover:opacity-90 transition">
                    ❌ Tolak
                </button>
            </div>
        </form>
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
                Tindakan ini akan mengubah status pesanan menjadi <strong>Ditolak</strong>
                dan tidak dapat dibatalkan. Customer akan melihat alasan penolakan.
            </p>
        </div>

        <form x-ref="rejectOrderFormShow"
              method="POST"
              action="{{ route('admin.orders.rejectOrder', $order->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">
                    Alasan Penolakan Pesanan <span class="text-red-400">*</span>
                </label>
                <textarea x-model="orderRejectReason"
                          name="order_rejection_reason"
                          rows="3"
                          placeholder="Contoh: Stok habis, area di luar jangkauan pengiriman, dll..."
                          class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl
                                 px-4 py-3 text-sm outline-none focus:border-red-500/60 resize-none
                                 placeholder:text-gray-600 transition"></textarea>
                <p x-show="orderRejectError" x-cloak
                   class="text-red-400 text-xs mt-1.5" x-text="orderRejectError"></p>
                @error('order_rejection_reason')
                <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
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
                    🚫 Ya, Tolak Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

</div>{{-- end x-data --}}

@endsection
