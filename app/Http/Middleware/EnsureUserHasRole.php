<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowedRoles = array_merge(...array_map(fn (string $role): array => explode(',', $role), $roles));
        $user = $request->user();
        abort_unless($user && in_array($user->role, $allowedRoles, true), 403);

        if ($user->role === 'sekretaris' && ! $request->routeIs('admin.dashboard', 'admin.penghuni.*', 'admin.alumni.*', 'admin.gallery.*', 'admin.foto-fasilitas.*', 'admin.kerusakan.*', 'admin.laporan', 'admin.laporan.*')) {
            abort(403);
        }

        if ($user->role === 'bendahara' && ! $request->routeIs('admin.dashboard', 'admin.pembayaran.*', 'admin.keuangan.*', 'admin.laporan', 'admin.laporan.*')) {
            abort(403);
        }

        return $next($request);
    }
}
