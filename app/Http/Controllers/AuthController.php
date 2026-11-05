<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View { return view('auth.login'); }

    public function superadminLogin(): View { return view('auth.superadmin-login'); }

    public function superadminAuthenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $email = strtolower(trim($credentials['email']));
        $user = User::whereRaw('LOWER(email) = ?', [$email])->where('role', 'superadmin')->first();
        if (! $user) return back()->withErrors(['email' => 'Email super admin tidak ditemukan.'])->onlyInput('email');
        if (! $user->active) return back()->withErrors(['email' => 'Akun super admin tidak aktif.'])->onlyInput('email');
        if (! Hash::check($credentials['password'], $user->password)) return back()->withErrors(['email' => 'Password super admin salah.'])->onlyInput('email');
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('last_activity_at', now()->timestamp);
        return to_route('admin.accounts.index');
    }

    public function register(): View { return view('auth.register'); }

    public function registerStore(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', 'unique:users,email'], 'password' => ['required', 'string', 'min:8', 'confirmed']]);
        User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make($data['password']), 'role' => 'admin', 'active' => true]);
        return to_route('login')->with('success', 'Akun admin berhasil dibuat. Silakan login.');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $user = User::where('email', $credentials['email'])->first();
        if ($user && ! $user->active) return back()->withErrors(['email' => 'Akun ini dinonaktifkan.'])->onlyInput('email');
        if ($user && ! in_array($user->role, ['admin', 'superadmin', 'bendahara', 'sekretaris'], true)) return back()->withErrors(['email' => 'Akun tidak memiliki akses admin.'])->onlyInput('email');
        if (! Auth::attempt($credentials, $request->boolean('remember'))) return back()->withErrors(['email' => 'Email atau password tidak valid.'])->onlyInput('email');
        $request->session()->regenerate();
        $request->session()->put('last_activity_at', now()->timestamp);
        return to_route('admin.dashboard');
    }

    public function forgotForm(): View { return view('auth.forgot-password'); }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return to_route('login');
    }
}
