<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAccountController extends Controller
{
    public function index(): View { return view('admin.accounts', ['admins' => User::whereIn('role', ['admin', 'bendahara', 'sekretaris'])->latest()->get()]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'unique:users,email'], 'password' => ['required', 'string', 'min:8'], 'role' => ['required', 'in:admin,bendahara,sekretaris']]);
        User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make($data['password']), 'role' => $data['role'], 'active' => true]);
        return back()->with('success', 'Akun pengurus ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'unique:users,email,'.$user->id], 'password' => ['nullable', 'string', 'min:8'], 'role' => ['required', 'in:admin,bendahara,sekretaris'], 'active' => ['required', 'boolean']]);
        $user->update(['name' => $data['name'], 'email' => $data['email'], 'role' => $data['role'], 'active' => $data['active'], ...($data['password'] ? ['password' => Hash::make($data['password'])] : [])]);
        return back()->with('success', 'Akun pengurus diperbarui.');
    }
}
