<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
               // return redirect(RouteServiceProvider::HOME);
                if ($request->user()->hasRole("24")) {
                    return redirect('/ppic');
                } else if ($request->user()->hasRole("15")) {
                    return redirect('/logistik/dashboard');
                } else if ($request->user()->hasRole("3")) {
                    return redirect('/manager-teknik');
                } else if ($request->user()->hasRole("23")) {
                    return redirect('/qc/dashboard');
                } else if ($request->user()->hasRole("26")) {
                    return redirect('/penjualan/dashboard');
                } else if ($request->user()->hasRole("13")) {
                    return redirect('/gbj/dashboard');
                } else if ($request->user()->hasRole("17")) {
                    return redirect('/produksi/dashboard');
                } else if ($request->user()->hasRole("12")) {
                    return redirect('/gk/dashboard');
                } else if ($request->user()->hasRole("9")) {
                    return redirect('/dc/dashboard');
                } else if ($request->user()->hasRole("2")) {
                    return redirect('/direksi/dashboard');
                } else if ($request->user()->hasRole("8")) {
                    return redirect('/penjualan/dashboard');
                } else if ($request->user()->hasRole("31")) {
                    return redirect('/gbj/dashboard');
                }
            }
        }
        return $next($request);
    }
}
