@extends('layouts.app')

@section('content')

@php
    $cart = session('cart', []);

    $total = collect($cart)
        ->sum(fn($item) => $item['price'] * $item['qty']);
@endphp

<div
    x-data="{
        loading:false,
        copied:false,

        copyRekening(){
            navigator.clipboard.writeText('0711982697');

            this.copied=true;

            setTimeout(()=>{
                this.copied=false;
            },2000);
        }
    }"

    class="max-w-5xl mx-auto px-4 py-6"
>

    {{-- HEADER --}}
    <div class="mb-8">

        <h1 class="text-3xl md:text-4xl font-black text-gray-900">
            Pembayaran
        </h1>

        <p class="text-gray-500 mt-2">
            Selesaikan pembayaran untuk memproses pesanan Anda.
        </p>

    </div>

    <div class="grid lg:grid-cols-2 gap-6">

        {{-- LEFT --}}
        <div
            class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6"
        >

            <h2 class="font-bold text-xl mb-5">
                Ringkasan Pesanan
            </h2>

            @if(count($cart))

                <div class="space-y-4">

                    @foreach($cart as $item)

                        <div
                            class="flex justify-between items-center border-b border-gray-100 pb-4"
                        >

                            <div>

                                <h3 class="font-semibold">
                                    {{ $item['name'] }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Qty: {{ $item['qty'] }}
                                </p>

                            </div>

                            <div
                                class="font-bold text-orange-600"
                            >
                                Rp {{ number_format($item['price'] * $item['qty'],0,',','.') }}
                            </div>

                        </div>

                    @endforeach

                </div>

                <div
                    class="mt-6 pt-5 border-t border-gray-200 flex justify-between items-center"
                >

                    <span class="font-bold text-lg">
                        Total
                    </span>

                    <span
                        class="text-2xl font-black text-orange-600"
                    >
                        Rp {{ number_format($total,0,',','.') }}
                    </span>

                </div>

            @else

                <div
                    class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center"
                >

                    Keranjang masih kosong.

                </div>

            @endif

        </div>

        {{-- RIGHT --}}
        <div
            class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6"
        >

            <h2
                class="font-bold text-xl mb-5"
            >
                Transfer Bank
            </h2>

            <div
                class="bg-orange-50 border border-orange-200 rounded-2xl p-5"
            >

                <div
                    class="flex items-center justify-between"
                >

                    <span class="font-bold">
                        Bank BCA
                    </span>

                    <span
                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold"
                    >
                        AKTIF
                    </span>

                </div>

                <div class="mt-5">

                    <p class="text-sm text-gray-500 mb-1">
                        Nomor Rekening
                    </p>

                    <div
                        class="flex items-center justify-between"
                    >

                        <span
                            class="font-black text-2xl text-gray-900"
                        >
                            0711982697
                        </span>

                        <button
                            type="button"
                            @click="copyRekening()"
                            class="px-4 py-2 rounded-xl bg-orange-500 text-white hover:bg-orange-600 transition"
                        >
                            Copy
                        </button>

                    </div>

                </div>

                <div class="mt-4">

                    <p class="text-sm text-gray-500">
                        Atas Nama
                    </p>

                    <p class="font-semibold text-gray-900">
                        Endang Triningrum Rizkaw
                    </p>

                </div>

            </div>

            {{-- COPY SUCCESS --}}
            <div
                x-show="copied"
                x-transition
                class="mt-4 bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl"
            >
                Nomor rekening berhasil disalin.
            </div>

            {{-- PETUNJUK --}}
            <div
                class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-5"
            >

                <h3
                    class="font-bold text-blue-900 mb-3"
                >
                    Cara Pembayaran
                </h3>

                <ol
                    class="space-y-2 text-sm text-blue-800"
                >

                    <li>1. Transfer sesuai total pembayaran.</li>

                    <li>2. Simpan bukti transfer.</li>

                    <li>3. Upload bukti pembayaran.</li>

                    <li>4. Tunggu verifikasi admin.</li>

                </ol>

            </div>

            {{-- BUTTON --}}
            <form
                method="POST"
                action="{{ route('payment.auto') }}"
                class="mt-6"
                @submit="loading=true"
            >

                @csrf

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-4 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg shadow-lg transition"
                >

                    <span
                        x-show="!loading"
                    >
                        Saya Sudah Transfer
                    </span>

                    <span
                        x-show="loading"
                        class="flex justify-center items-center gap-3"
                    >

                        <svg
                            class="animate-spin h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                            ></path>

                        </svg>

                        Memproses...

                    </span>

                </button>

            </form>

        </div>

    </div>

</div>

@endsection