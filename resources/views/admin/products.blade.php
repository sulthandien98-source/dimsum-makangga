@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')

<!-- TAMBAH PRODUK -->
<div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 mb-8 shadow-xl">

    <h2 class="text-lg font-bold mb-4 text-purple-300">➕ Tambah Produk Baru</h2>

    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf

        <div class="grid md:grid-cols-3 gap-4">
            <input type="text" name="name" placeholder="Nama Produk"
                class="p-3 rounded-xl bg-white/20 border border-white/30 text-white
                       placeholder-white/60 focus:outline-none focus:border-purple-400"
                value="{{ old('name') }}" required>

            <input type="number" name="price" placeholder="Harga (Rp)"
                class="p-3 rounded-xl bg-white/20 border border-white/30 text-white
                       placeholder-white/60 focus:outline-none focus:border-purple-400"
                value="{{ old('price') }}" required>

            <input type="text" name="description" placeholder="Deskripsi (opsional)"
                class="p-3 rounded-xl bg-white/20 border border-white/30 text-white
                       placeholder-white/60 focus:outline-none focus:border-purple-400"
                value="{{ old('description') }}">
        </div>

        <button class="mt-4 bg-purple-500 hover:bg-purple-600 transition px-6 py-2.5
                       rounded-xl font-semibold text-sm">
            Tambah Produk
        </button>

    </form>

</div>

<!-- LIST PRODUK -->
<div class="space-y-4">

    @forelse($products as $p)
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 shadow-xl">

        <form method="POST" action="{{ route('admin.products.update', $p->id) }}">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-3 gap-4 mb-3">
                <input type="text" name="name" value="{{ $p->name }}"
                    class="p-3 rounded-xl bg-white/20 border border-white/30 text-white
                           focus:outline-none focus:border-yellow-400" required>

                <input type="number" name="price" value="{{ $p->price }}"
                    class="p-3 rounded-xl bg-white/20 border border-white/30 text-white
                           focus:outline-none focus:border-yellow-400" required>

                <input type="text" name="description" value="{{ $p->description }}"
                    class="p-3 rounded-xl bg-white/20 border border-white/30 text-white
                           focus:outline-none focus:border-yellow-400">
            </div>

            <div class="flex gap-3">
                <button class="bg-yellow-400 text-black px-4 py-2 rounded-xl
                               hover:bg-yellow-500 transition text-sm font-semibold">
                    ✏️ Update
                </button>
            </div>

        </form>

        <!-- DELETE (form terpisah) -->
        <form method="POST" action="{{ route('admin.products.delete', $p->id) }}"
              onsubmit="return confirm('Hapus produk ini?')" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="bg-red-500/80 hover:bg-red-600 transition px-4 py-2
                           rounded-xl text-sm font-semibold">
                🗑️ Hapus
            </button>
        </form>

    </div>
    @empty
        <div class="text-center py-10 text-gray-400">
            Belum ada produk. Tambahkan produk pertama di atas! 🍽️
        </div>
    @endforelse

</div>

@endsection
