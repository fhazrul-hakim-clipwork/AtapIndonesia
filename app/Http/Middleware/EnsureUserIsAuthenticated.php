<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Tentukan guard berdasarkan URL
        |--------------------------------------------------------------------------
        |
        | Admin:
        | /dashboard/admin/...
        |
        | Pembeli:
        | route dashboard lainnya.
        |
        */

        if ($request->is('dashboard/admin*')) {
            $guard = 'admin';
        } else {
            $guard = 'pembeli';
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil guard secara eksplisit
        |--------------------------------------------------------------------------
        |
        | Jangan menggunakan Auth::check() / Auth::user()
        | karena aplikasi menggunakan dua session guard:
        |
        | admin
        | pembeli
        |
        */

        $auth = Auth::guard($guard);


        /*
        |--------------------------------------------------------------------------
        | Cek apakah user sudah login
        |--------------------------------------------------------------------------
        */

        if (!$auth->check()) {

            return redirect()
                ->route('login')
                ->with(
                    'sukses',
                    $guard === 'admin'
                        ? 'Silakan login sebagai admin terlebih dahulu.'
                        : 'Silakan login sebagai pembeli terlebih dahulu.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil user dari guard yang sesuai
        |--------------------------------------------------------------------------
        */

        $user = $auth->user();


        /*
        |--------------------------------------------------------------------------
        | Pastikan user tersedia
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            $auth->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Sesi login tidak valid. Silakan login kembali.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi role ADMIN
        |--------------------------------------------------------------------------
        */

        if ($guard === 'admin' && $user->role !== 'admin') {

            /*
            | Hanya logout guard ADMIN.
            |
            | Guard PEMBELI tidak disentuh.
            |
            */

            $auth->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Akun Anda tidak memiliki akses admin.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi role PEMBELI
        |--------------------------------------------------------------------------
        */

        if ($guard === 'pembeli' && $user->role !== 'pembeli') {

            /*
            | Hanya logout guard PEMBELI.
            |
            | Guard ADMIN tidak disentuh.
            |
            */

            $auth->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Akun Anda tidak memiliki akses pembeli.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Lanjutkan request
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}