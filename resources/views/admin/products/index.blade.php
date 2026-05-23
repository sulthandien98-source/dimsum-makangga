@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-white">🍽️ Manajemen Produk</h1>
            <p class="text-sm text-gray-500">Kelola menu, stok, dan status produk</p>
        </div>
        <button onclick="openModal('add')" class="btn-orange">
            + Tambah Produk
        </button>
    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
    <div x-data="{show:true}" x-init="setTimeout(()=>show=false,3000)" x-show="show"
         class="bg-green-500/20 border border-green-500 text-green-400 p-3 rounded-xl text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-500/20 border border-red-500 text-red-400 p-3 rounded-xl text-sm">
        ❌ {{ $errors->first() }}
    </div>
    @endif

    <!-- TABLE -->
    <div class="admin-card overflow-x-auto">

        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-[#222]">
                    <th class="text-left pb-3">Produk</th>
                    <th class="text-center pb-3">Harga</th>
                    <th class="text-center pb-3">Stok</th>
                    <th class="text-center pb-3">Status</th>
                    <th class="text-right pb-3">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1a1a1a]">
                @forelse($products as $p)
                <tr class="hover:bg-[#1a1a1a] transition">

                    <!-- NAMA -->
                    <td class="py-4">
                        <p class="text-white font-semibold">{{ $p->name }}</p>
                        @if($p->description)
                        <p class="text-xs text-gray-500">{{ Str::limit($p->description, 50) }}</p>
                        @endif
                        <p class="text-xs text-gray-600">ID: #{{ $p->id }}</p>
                    </td>

                    <!-- HARGA -->
                    <td class="text-center text-gray-300 font-semibold">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </td>

                    <!-- STOK -->
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-white font-bold {{ $p->stock <= 5 ? 'text-red-400' : '' }}">
                                {{ $p->stock }}
                            </span>
                            <form action="{{ route('admin.products.addStock', $p->id) }}"
                                  method="POST"
                                  class="flex items-center gap-1">
                                @csrf
                                <input type="number" name="stock" min="1" max="9999"
                                       placeholder="+stok"
                                       class="w-16 text-center bg-[#1a1a1a] border border-[#2a2a2a]
                                              text-white rounded-lg px-1 py-1 text-xs focus:border-orange-500 outline-none">
                                <button type="submit"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg
                                           bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold">
                                    +
                                </button>
                            </form>
                        </div>
                    </td>

                    <!-- STATUS -->
                    <td class="text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $p->is_available
                                ? 'bg-green-500/20 text-green-400'
                                : 'bg-red-500/20 text-red-400' }}">
                            {{ $p->is_available ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">

                            <!-- EDIT -->
                            <button onclick="openEdit({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->price }}, {{ $p->stock }}, '{{ addslashes($p->description ?? '') }}')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold
                                       bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition">
                                ✏️ Edit
                            </button>

                            <!-- TOGGLE -->
                            <form action="{{ route('admin.products.toggle', $p->id) }}"
                                  method="POST" class="inline">
                                @csrf
                                <button class="px-3 py-1.5 rounded-lg text-xs font-bold
                                    bg-orange-500/20 text-orange-400 hover:bg-orange-500/30 transition">
                                    {{ $p->is_available ? '🔴 Matikan' : '🟢 Aktifkan' }}
                                </button>
                            </form>

                            <!-- DELETE -->
                            <form action="{{ route('admin.products.delete', $p->id) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Hapus produk {{ addslashes($p->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg text-xs font-bold
                                    bg-red-500/20 text-red-400 hover:bg-red-500/30 transition">
                                    🗑️ Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-500">
                        Belum ada produk. Klik "+ Tambah Produk" untuk menambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

<!-- MODAL TAMBAH PRODUK -->
<div id="modal-add"
     class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-[#161616] w-full max-w-md p-6 rounded-2xl border border-[#262626]">
        <h2 class="text-lg font-bold text-white mb-4">➕ Tambah Produk</h2>

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="space-y-3">

                <input type="text" name="name" required
                    placeholder="Nama produk *"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm">

                <input type="number" name="price" required min="0"
                    placeholder="Harga (Rp) *"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm">

                <input type="number" name="stock" required min="0"
                    placeholder="Stok awal *"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm">

                <textarea name="description" rows="2"
                    placeholder="Deskripsi (opsional)"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm resize-none"></textarea>

            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('add')"
                        class="px-4 py-2 rounded-xl border border-gray-600 text-gray-300
                               hover:bg-[#1f1f1f] transition text-sm">
                    Batal
                </button>
                <button type="submit" class="btn-orange">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT PRODUK -->
<div id="modal-edit"
     class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-[#161616] w-full max-w-md p-6 rounded-2xl border border-[#262626]">
        <h2 class="text-lg font-bold text-white mb-4">✏️ Edit Produk</h2>

        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-3">

                <input type="text" id="edit-name" name="name" required
                    placeholder="Nama produk *"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm">

                <input type="number" id="edit-price" name="price" required min="0"
                    placeholder="Harga (Rp) *"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm">

                <input type="number" id="edit-stock" name="stock" required min="0"
                    placeholder="Stok"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm">

                <textarea id="edit-description" name="description" rows="2"
                    placeholder="Deskripsi (opsional)"
                    class="w-full bg-[#1a1a1a] border border-[#2a2a2a] text-white rounded-xl px-3 py-2.5
                           focus:border-orange-500 outline-none text-sm resize-none"></textarea>

            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('edit')"
                        class="px-4 py-2 rounded-xl border border-gray-600 text-gray-300
                               hover:bg-[#1f1f1f] transition text-sm">
                    Batal
                </button>
                <button type="submit" class="btn-orange">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(type) {
    document.getElementById('modal-' + type).classList.remove('hidden');
}

function closeModal(type) {
    document.getElementById('modal-' + type).classList.add('hidden');
}

function openEdit(id, name, price, stock, description) {
    document.getElementById('edit-name').value        = name;
    document.getElementById('edit-price').value       = price;
    document.getElementById('edit-stock').value       = stock;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-form').action       = '/admin/products/' + id;
    openModal('edit');
}

// Close modal on ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeModal('add');
        closeModal('edit');
    }
});
</script>

@endsection
