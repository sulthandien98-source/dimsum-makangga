@extends('layouts.app')

@section('title', 'Menu')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900">
        Menu Dimsum Premium
    </h1>

    <p class="text-slate-500 mt-3">
        Nikmati dimsum berkualitas dengan tampilan modern dan responsive.
    </p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($products as $p)

    <div class="card-modern overflow-hidden">

        <div class="aspect-video bg-slate-100 overflow-hidden">
            <img
                src="{{ $p->image ? asset('storage/'.$p->image) : 'https://placehold.co/600x400' }}"
                alt="{{ $p->name }}"
                class="w-full h-full object-cover hover:scale-105 transition duration-500"
            >
        </div>

        <div class="p-5">

            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="font-bold text-xl text-slate-900">
                        {{ $p->name }}
                    </h2>

                    <p class="text-slate-500 text-sm mt-2 line-clamp-2">
                        {{ $p->description }}
                    </p>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between">
                <div class="text-orange-500 font-extrabold text-xl">
                    Rp {{ number_format($p->price,0,',','.') }}
                </div>

                <button
                    class="btn-primary"
                    @click="addToCart({{ $p->id }})"
                >
                    + Keranjang
                </button>
            </div>

        </div>

    </div>

    @empty

    <div class="col-span-full">
        <div class="card-modern p-10 text-center">
            <p class="text-slate-500">
                Produk belum tersedia.
            </p>
        </div>
    </div>

    @endforelse

</div>

<script>
function addToCart(id) {
    console.log("Add to cart:", id);
}
</script>

@endsection
