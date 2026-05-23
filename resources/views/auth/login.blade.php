@extends('layouts.guest')

@section('content')

<div class="text-center mb-6">
    <h1 class="text-2xl font-black" style="color:#fbbf24;">
        🥟 Dimsum Mak'Angga
    </h1>
    <p class="text-sm" style="color:rgba(254,243,199,.6);">
        Silakan login untuk mulai memesan
    </p>
</div>

<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">
@csrf

    <!-- EMAIL -->
    <div class="mb-4">
        <label class="text-sm font-semibold" style="color:#fef3c7;">
            Email
        </label>
        <input type="email" name="email" required autofocus
            class="w-full mt-1 p-3 rounded-xl text-sm"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
        @error('email')
            <p class="text-xs mt-1" style="color:#fca5a5;">{{ $message }}</p>
        @enderror
    </div>

    <!-- PASSWORD -->
    <div class="mb-4">
        <label class="text-sm font-semibold" style="color:#fef3c7;">
            Password
        </label>
        <input type="password" name="password" required
            class="w-full mt-1 p-3 rounded-xl text-sm"
            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,180,60,0.2); color:#fef3c7;">
        @error('password')
            <p class="text-xs mt-1" style="color:#fca5a5;">{{ $message }}</p>
        @enderror
    </div>

    <!-- REMEMBER -->
    <div class="flex items-center justify-between mb-4 text-sm">
        <label class="flex items-center gap-2" style="color:#fef3c7;">
            <input type="checkbox" name="remember">
            Ingat saya
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" 
               style="color:#f97316;" class="hover:underline">
                Lupa password?
            </a>
        @endif
    </div>

    <!-- BUTTON -->
    <button type="submit"
        class="w-full py-3 rounded-xl font-black text-sm transition"
        style="background:linear-gradient(135deg,#f97316,#ea580c); color:white;">
        🔐 Login
    </button>

    <!-- LINK -->
    <p class="text-center text-sm mt-4" style="color:rgba(254,243,199,.6);">
        Belum punya akun?
        <a href="{{ route('register') }}" 
           class="font-bold hover:underline"
           style="color:#f97316;">
            Daftar
        </a>
    </p>

</form>

@endsection