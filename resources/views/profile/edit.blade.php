@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', '👤 Profil Saya')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="card">

        <h2 class="text-xl font-black mb-6" style="color:#fbbf24;">
            🪪 Profil Saya
        </h2>

        <!-- UPDATE PROFILE -->
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="text-sm font-bold block mb-1" style="color:#fbbf24;">
                    Nama
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="input"
                       required>
            </div>

            <div>
                <label class="text-sm font-bold block mb-1" style="color:#fbbf24;">
                    Email
                </label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="input"
                       required>
            </div>

            <button type="submit" class="btn-primary w-full">
                💾 Simpan Perubahan
            </button>
        </form>

        <!-- DIVIDER -->
        <div class="my-6 border-t" style="border-color: rgba(255,180,60,0.2);"></div>

        <!-- DELETE ACCOUNT -->
        <div>
            <h3 class="text-sm font-bold mb-2" style="color:#f87171;">
                ⚠️ Hapus Akun
            </h3>

            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-3">
                @csrf
                @method('DELETE')

                <input type="password"
                       name="password"
                       placeholder="Masukkan password"
                       class="input"
                       required>

                <button type="submit"
                        class="w-full py-2 rounded-xl font-bold transition"
                        style="background:rgba(220,38,38,.2); color:#fca5a5; border:1px solid rgba(220,38,38,.4);"
                        onmouseover="this.style.background='rgba(220,38,38,.4)'"
                        onmouseout="this.style.background='rgba(220,38,38,.2)'">
                    ❌ Hapus Akun
                </button>
            </form>
        </div>

    </div>

</div>

@endsection