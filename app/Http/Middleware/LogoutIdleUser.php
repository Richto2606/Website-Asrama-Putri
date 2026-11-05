<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogoutIdleUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $last = $request->session()->get('last_activity_at');
        if (Auth::check() && $last && now()->timestamp - $last > 43200) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return to_route('login')->withErrors(['email' => 'Sesi berakhir karena tidak aktif selama 30 menit.']);
        }
        if (Auth::check()) $request->session()->put('last_activity_at', now()->timestamp);
        return $next($request);
    }
}
