<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🔒 Jika belum login → ke login
        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // ⛔ Jika bukan admin → blokir
        if (!$user || !$user->isAdmin()) {

            // 🔥 OPTIONAL: kalau request dari admin area, jangan kirim ke menu user
            if ($request->is('admin/*')) {
                abort(403, 'Akses ditolak. Halaman ini khusus Admin.');
            }

            return redirect()
                ->route('menu')
                ->with('error', 'Akses ditolak. Halaman ini khusus Admin.');
        }

        return $next($request);
    }
}