<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Cek jika ada intended URL (misalnya dari redirect login saat mau checkout)
        $intended = $request->session()->get('url.intended');

        // 👑 ADMIN → dashboard admin
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // 👤 USER → kembali ke halaman tujuan atau menu
        return redirect()->intended(route('menu'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout kembali ke menu (public)
        return redirect()->route('menu');
    }
}
