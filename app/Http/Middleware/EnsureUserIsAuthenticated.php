<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() && !session('is_logged_in')) {
            return redirect()->route('login')->with('sukses', 'Anda harus masuk untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
