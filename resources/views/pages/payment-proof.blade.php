@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-6">

    {{-- HEADER --}}
    <div class="mb-8">

        <h1 class="text-3xl md:text-4xl font-black text-gray-900">
            Upload Bukti Pembayaran
        </h1>

        <p class="text-gray-500 mt-2">
            Upload bukti transfer untuk mempercepat proses verifikasi pesanan.
        </p>

    </div>

    {{-- ORDER CARD --}}
    <div
        class="bg-white border border-gray-100 rounded-3xl shadow-lg p-6 mb-6"
    >

        <div class="flex justify-between items-center mb-4">

            <div>

                <p class="text-sm text-gray-500">
                    Nomor Pesanan
                </p>

                <h2 class="font-black text-xl">
                    #{{ $order->id }}
                </h2>

            </div>

            <div>

                <span
                    class="px-4 py-2 rounded-full bg-orange-100 text-orange-700 text-sm font-bold"
                >
                    {{ $order->status_label }}
                </span>

            </div>

        </div>

        <div
            class="grid md:grid-cols-2 gap-5"
        >

            <div
                class="bg-orange-50 border border-orange-100 rounded-2xl p-4"
            >

                <p class="text-sm text-gray-500 mb-2">
                    Transfer Ke
                </p>

                <h3 class="font-black text-lg">
                    BCA
                </h3>

                <p class="font-bold">
                    0711982697
                </p>

                <p class="text-sm text-gray-600">
                    Endang Triningrum Rizkaw
                </p>

            </div>

            <div
                class="bg-green-50 border border-green-100 rounded-2xl p-4"
            >

                <p class="text-sm text-gray-500 mb-2">
                    Total Pembayaran
                </p>

                <h3
                    class="text-2xl font-black text-green-600"
                >
                    Rp {{ number_format($order->total_price,0,',','.') }}
                </h3>

            </div>

        </div>

    </div>

    {{-- UPLOAD FORM --}}
    <div
        class="bg-white border border-gray-100 rounded-3xl shadow-lg p-6"
        x-data="{

            preview:null,
            fileName:'',
            fileSize:'',
            loading:false,
            dragging:false,
            error:'',

            handleFile(file){

                this.error='';

                const allowed=[
                    'image/jpeg',
                    'image/jpg',
                    'image/png'
                ];

                if(!allowed.includes(file.type)){
                    this.error='Format file harus JPG, JPEG, atau PNG';
                    return;
                }

                if(file.size > 2 * 1024 * 1024){
                    this.error='Ukuran maksimal 2MB';
                    return;
                }

                this.fileName=file.name;
                this.fileSize=(file.size/1024).toFixed(1)+' KB';

                const reader=new FileReader();

                reader.onload=(e)=>{
                    this.preview=e.target.result;
                };

                reader.readAsDataURL(file);

                const dt=new DataTransfer();
                dt.items.add(file);

                document
                    .getElementById('payment_proof')
                    .files=dt.files;
            }
        }"
    >

        @if(session('error'))

            <div
                class="mb-5 bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl"
            >
                {{ session('error') }}
            </div>

        @endif

        @if($errors->has('payment_proof'))

            <div
                class="mb-5 bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl"
            >
                {{ $errors->first('payment_proof') }}
            </div>

        @endif

        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('payment.proof.upload',$order->id) }}"
            @submit="

                if(!preview){

                    error='Pilih file terlebih dahulu';

                    $event.preventDefault();

                    return;
                }

                loading=true;
            "
        >

            @csrf

            <h2 class="font-bold text-lg mb-5">
                Bukti Transfer
            </h2>

            {{-- DROPZONE --}}
            <div
                @dragover.prevent="dragging=true"
                @dragleave.prevent="dragging=false"
                @drop.prevent="
                    dragging=false;
                    handleFile($event.dataTransfer.files[0]);
                "
                class="mb-5"
            >

                <label
                    for="payment_proof"
                    class="block cursor-pointer"
                >

                    <template x-if="!preview">

                        <div
                            :class="dragging
                                ? 'border-orange-400 bg-orange-50'
                                : 'border-gray-300'"
                            class="border-2 border-dashed rounded-3xl p-10 text-center transition"
                        >

                            <div class="text-5xl mb-4">
                                📸
                            </div>

                            <h3 class="font-bold text-lg mb-2">
                                Upload Bukti Pembayaran
                            </h3>

                            <p class="text-gray-500 mb-4">
                                Klik atau drag foto ke area ini
                            </p>

                            <span
                                class="inline-block px-5 py-3 bg-orange-500 text-white rounded-2xl font-semibold"
                            >
                                Pilih Foto
                            </span>

                            <p class="mt-4 text-xs text-gray-400">
                                JPG, JPEG, PNG • Maksimal 2MB
                            </p>

                        </div>

                    </template>

                    <template x-if="preview">

                        <div
                            class="rounded-3xl overflow-hidden border border-orange-200"
                        >

                            <img
                                :src="preview"
                                class="w-full max-h-[400px] object-contain bg-gray-50"
                            >

                            <div
                                class="p-4 bg-gray-50 border-t"
                            >

                                <div
                                    class="flex justify-between items-center"
                                >

                                    <div>

                                        <p
                                            class="font-bold text-sm"
                                            x-text="fileName"
                                        ></p>

                                        <p
                                            class="text-xs text-gray-500"
                                            x-text="fileSize"
                                        ></p>

                                    </div>

                                    <span
                                        class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold"
                                    >
                                        Siap Upload
                                    </span>

                                </div>

                            </div>

                        </div>

                    </template>

                </label>

                <input
                    id="payment_proof"
                    type="file"
                    name="payment_proof"
                    accept=".jpg,.jpeg,.png"
                    class="hidden"
                    @change="

                        if($event.target.files[0]){

                            handleFile(
                                $event.target.files[0]
                            );

                        }

                    "
                >

            </div>

            {{-- ERROR --}}
            <div
                x-show="error"
                x-cloak
                class="mb-5 bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl"
            >

                <span x-text="error"></span>

            </div>

            {{-- ACTION --}}
            <div class="space-y-3">

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-2xl font-bold transition shadow-lg"
                >

                    <span x-show="!loading">
                        Upload Bukti Pembayaran
                    </span>

                    <span
                        x-show="loading"
                        class="flex justify-center items-center gap-2"
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

                        Mengupload...

                    </span>

                </button>

                <a
                    href="{{ route('order.waiting',$order->id) }}"
                    class="block text-center py-3 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition"
                >
                    Upload Nanti
                </a>

            </div>

        </form>

    </div>

</div>

@endsection