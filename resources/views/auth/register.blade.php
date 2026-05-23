@extends('layouts.guest')

@section('content')

<div class="text-center mb-6">
    <h1 class="text-2xl font-black" style="color:#fbbf24;">
        ✍️ Buat Akun
    </h1>
    <p class="text-sm" style="color:rgba(254,243,199,.6);">
        Daftar untuk mulai memesan dimsum 🍽️
    </p>
</div>

<form method="POST" action="{{ route('register') }}">
@csrf

    <!-- NAMA -->
    <div class="mb-4">
        <label class="text-sm font-semibold" style="color:#fef3c7;">
            Nama
        </label>
        <input type="text" name="name" required
            class="w-full mt-1 p-3 rounded-xl text-sm"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
    </div>

    <!-- EMAIL -->
    <div class="mb-4">
        <label class="text-sm font-semibold" style="color:#fef3c7;">
            Email
        </label>
        <input type="email" name="email" required
            class="w-full mt-1 p-3 rounded-xl text-sm"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
    </div>

    <!-- PASSWORD -->
    <div class="mb-4">
        <label class="text-sm font-semibold" style="color:#fef3c7;">
            Password
        </label>
        <input type="password" name="password" required
            class="w-full mt-1 p-3 rounded-xl text-sm"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
    </div>

    <!-- CONFIRM -->
    <div class="mb-4">
        <label class="text-sm font-semibold" style="color:#fef3c7;">
            Konfirmasi Password
        </label>
        <input type="password" name="password_confirmation" required
            class="w-full mt-1 p-3 rounded-xl text-sm"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
    </div>

    <!-- BUTTON -->
    <button type="submit"
        class="w-full py-3 rounded-xl font-black text-sm transition"
        style="background:linear-gradient(135deg,#f97316,#ea580c); color:white;">
        ✍️ Daftar
    </button>

    <!-- LOGIN LINK -->
    <p class="text-center text-sm mt-4" style="color:rgba(254,243,199,.6);">
        Sudah punya akun?
        <a href="{{ route('login') }}" 
           class="font-bold hover:underline"
           style="color:#f97316;">
            Login
        </a>
    </p>

</form>

@endsection