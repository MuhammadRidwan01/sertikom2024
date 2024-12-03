<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
{
    // Simpan user yang sedang login
    $user = auth()->user();

    // Pastikan pengguna sudah login
    if (!$user) {
        return redirect()->route('login');
    }

    // Jika admin, izinkan semua akses
    if ($user->isAdmin()) {
        return $next($request);
    }

    // Cek role dan redirect jika tidak sesuai
    if ($user->role !== $role) {
        return redirect()
            ->route($user->role . '.dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }

    // Lanjutkan request
    return $next($request);
}

}
