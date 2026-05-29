@extends('layouts.app')

@section('content')

<div class="flex min-h-screen items-center justify-center px-4 py-10">

    <div class="card-ui w-full max-w-md p-8 animate-slide">

        <div class="mb-8 text-center">

            <h1 class="mb-2 text-4xl font-bold">
                Create Account
            </h1>

            <p class="text-muted">
                Daftar akun baru
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('register') }}"
            class="space-y-5"
        >

            @csrf

            <div>

                <label class="mb-2 block font-medium">
                    Nama
                </label>

                <input
                    type="text"
                    name="name"
                    class="input-ui"
                    required
                >

            </div>

            <div>

                <label class="mb-2 block font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="input-ui"
                    required
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

            <div>

                <label class="mb-2 block font-medium">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="input-ui"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn-primary w-full"
            >
                Register
            </button>

        </form>

    </div>

</div>

@endsection