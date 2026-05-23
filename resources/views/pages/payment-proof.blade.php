@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')

<div class="max-w-xl mx-auto px-4">

    <h1 class="text-2xl font-black mb-6 flex items-center gap-2 text-white">
        📸 Upload Bukti Pembayaran
    </h1>

    {{-- ORDER SUMMARY --}}
    <div class="card mb-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-amber-300/70 font-bold uppercase tracking-wide">Pesanan #{{ $order->id }}</span>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-500/20 text-orange-400 border border-orange-500/30">
                {{ $order->status_label }}
            </span>
        </div>
        <div class="bg-black/20 rounded-xl p-4">
            <p class="text-sm text-amber-200/60 mb-1">Transfer ke:</p>
            <p class="font-bold text-white">BCA — 0711982697</p>
            <p class="text-sm text-amber-200/60">Atas Nama: Endang Triningrum Rizkaw</p>
            <div class="border-t border-white/10 mt-3 pt-3 flex justify-between items-center">
                <span class="text-amber-200/60 text-sm">Total Bayar</span>
                <span class="text-orange-400 font-black text-xl">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- UPLOAD FORM --}}
    <div class="card"
         x-data="{
             dragging: false,
             preview: null,
             fileName: '',
             fileSize: '',
             error: '',
             loading: false,
             handleFile(file) {
                 this.error = '';
                 const allowed = ['image/jpeg','image/jpg','image/png'];
                 if (!allowed.includes(file.type)) {
                     this.error = 'Format harus JPG, JPEG, atau PNG.';
                     return;
                 }
                 if (file.size > 2 * 1024 * 1024) {
                     this.error = 'Ukuran file maksimal 2MB.';
                     return;
                 }
                 this.fileName = file.name;
                 this.fileSize = (file.size / 1024).toFixed(1) + ' KB';
                 const reader = new FileReader();
                 reader.onload = e => { this.preview = e.target.result; };
                 reader.readAsDataURL(file);
                 const dt = new DataTransfer();
                 dt.items.add(file);
                 document.getElementById('proof-input').files = dt.files;
             }
         }">

        @if(session('error'))
        <div class="bg-red-500/20 border border-red-500/40 text-red-400 p-3 rounded-xl mb-4 text-sm flex items-center gap-2">
            <span>⚠️</span> {{ session('error') }}
        </div>
        @endif

        @if($errors->has('payment_proof'))
        <div class="bg-red-500/20 border border-red-500/40 text-red-400 p-3 rounded-xl mb-4 text-sm flex items-center gap-2">
            <span>⚠️</span> {{ $errors->first('payment_proof') }}
        </div>
        @endif

        <form method="POST"
              action="{{ route('payment.proof.upload', $order->id) }}"
              enctype="multipart/form-data"
              @submit="if(!preview){ $event.preventDefault(); error='Pilih file terlebih dahulu.'; return; } loading=true">
            @csrf

            <h2 class="font-bold text-base text-amber-100 mb-4">
                📎 Upload Bukti Transfer
            </h2>

            {{-- DRAG & DROP AREA --}}
            <div class="relative mb-4"
                 @dragover.prevent="dragging=true"
                 @dragleave.prevent="dragging=false"
                 @drop.prevent="dragging=false; handleFile($event.dataTransfer.files[0])">

                <label for="proof-input"
                       class="block cursor-pointer">

                    {{-- PREVIEW STATE --}}
                    <template x-if="preview">
                        <div class="relative rounded-2xl overflow-hidden border-2 border-orange-500/50 bg-black/30">
                            <img :src="preview"
                                 alt="Preview"
                                 class="w-full max-h-64 object-contain">
                            <div class="absolute bottom-0 left-0 right-0 bg-black/70 backdrop-blur-sm p-3 flex items-center justify-between">
                                <div>
                                    <p class="text-white text-xs font-bold" x-text="fileName"></p>
                                    <p class="text-gray-400 text-xs" x-text="fileSize"></p>
                                </div>
                                <span class="text-green-400 text-xs font-bold bg-green-500/20 px-2 py-1 rounded-lg">✓ Siap</span>
                            </div>
                        </div>
                    </template>

                    {{-- EMPTY STATE --}}
                    <template x-if="!preview">
                        <div :class="dragging ? 'border-orange-400 bg-orange-500/10 scale-[1.02]' : 'border-white/10 bg-white/[0.03]'"
                             class="border-2 border-dashed rounded-2xl p-8 text-center transition-all duration-200">
                            <div class="text-4xl mb-3">📁</div>
                            <p class="text-amber-100 font-bold mb-1">Drag & Drop foto disini</p>
                            <p class="text-amber-200/40 text-sm mb-3">atau klik untuk pilih file</p>
                            <span class="inline-block px-4 py-2 bg-orange-500/20 border border-orange-500/40 text-orange-400 rounded-xl text-sm font-bold">
                                Pilih Foto
                            </span>
                            <p class="text-amber-200/30 text-xs mt-3">JPG, JPEG, PNG • Maks 2MB</p>
                        </div>
                    </template>

                </label>

                <input type="file"
                       id="proof-input"
                       name="payment_proof"
                       accept="image/jpg,image/jpeg,image/png"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                       @change="if($event.target.files[0]) handleFile($event.target.files[0])">
            </div>

            {{-- ERROR MESSAGE --}}
            <div x-show="error"
                 x-cloak
                 class="bg-red-500/20 border border-red-500/40 text-red-400 p-3 rounded-xl mb-4 text-sm flex items-center gap-2">
                <span>⚠️</span>
                <span x-text="error"></span>
            </div>

            {{-- GANTI FOTO --}}
            <template x-if="preview">
                <button type="button"
                        @click="preview=null; fileName=''; fileSize=''; document.getElementById('proof-input').value=''"
                        class="w-full mb-3 py-2 rounded-xl border border-white/10 text-amber-200/50 text-sm hover:bg-white/5 transition">
                    🔄 Ganti Foto
                </button>
            </template>

            {{-- SUBMIT --}}
            <button type="submit"
                    :disabled="loading"
                    :class="loading ? 'opacity-60 cursor-not-allowed' : 'hover:bg-orange-600'"
                    class="w-full py-3.5 bg-orange-500 transition rounded-xl font-black text-white text-base">
                <span x-show="!loading">📤 Upload Bukti Pembayaran</span>
                <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Mengupload...
                </span>
            </button>

        </form>

        {{-- SKIP LINK --}}
        <div class="mt-4 text-center">
            <a href="{{ route('order.waiting', $order->id) }}"
               class="text-amber-200/40 text-xs hover:text-amber-200/60 transition underline">
                Upload nanti →
            </a>
        </div>

    </div>

</div>

@endsection
