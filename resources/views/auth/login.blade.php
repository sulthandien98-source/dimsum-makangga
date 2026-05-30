@extends('layouts.app')

@section('content')

<div class="flex min-h-screen items-center justify-center px-4 py-10">

<div class="card-ui w-full max-w-md p-8 animate-slide">

    <div class="mb-8 text-center">

        <h1 class="mb-2 text-4xl font-bold">
            Welcome Back
        </h1>

        <p class="text-muted">
            Login ke akun Anda
        </p>

    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl bg-green-500/10 border border-green-500/20 p-3 text-sm text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-400">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('login') }}"
        class="space-y-5"
    >

        @csrf

        <div>

            <label class="mb-2 block font-medium">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="input-ui"
                required
                autofocus
            >

        </div>

        <div>

            <label class="mb-2 block font-medium">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="input-ui"
                required
            >

        </div>

        <div class="flex items-center justify-between text-sm">

            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-600"
                >
                <span class="text-muted">
                    Ingat Saya
                </span>
            </label>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-orange-500 hover:text-orange-400 transition"
                >
                    Lupa Password?
                </a>
            @endif

        </div>

        <button
            type="submit"
            class="btn-primary w-full"
        >
            Login
        </button>

    </form>

    <div class="mt-6 border-t border-gray-700 pt-4 text-center">

        <p class="text-sm text-gray-400">
            Belum memiliki akun?
        </p>

        <a
            href="{{ route('register') }}"
            class="mt-2 inline-block font-bold text-orange-500 hover:text-orange-400 transition"
        >
            Daftar Sekarang
        </a>

    </div>

</div>

</div>

@endsection