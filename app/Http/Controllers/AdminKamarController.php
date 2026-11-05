<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminKamarController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['nomor_kamar' => ['required', 'string', 'max:50', 'unique:kamars,nomor_kamar'], 'nama_kamar' => ['nullable', 'string', 'max:100']]);
        Kamar::create(['nomor_kamar' => $data['nomor_kamar'], 'nama_kamar' => $data['nama_kamar'] ?? null, 'kapasitas' => 2, 'terisi' => 0, 'status' => 'tersedia']);

        return back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function update(Request $request, Kamar $kamar): RedirectResponse
    {
        $data = $request->validate([
            'nomor_kamar' => ['required', 'string', 'max:50', 'unique:kamars,nomor_kamar,'.$kamar->id],
            'nama_kamar' => ['nullable', 'string', 'max:100'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:20'],
            'terisi' => ['required', 'integer', 'min:0', 'lte:kapasitas'],
            'status' => ['required', 'in:tersedia,diperbaiki,ditutup'],
        ]);

        $penghuniAktual = $kamar->penghunis()->where('role', 'penghuni')->count();
        if ($data['terisi'] < $penghuniAktual) {
            return back()->withErrors(['terisi' => 'Jumlah terisi tidak boleh kurang dari penghuni aktif ('.$penghuniAktual.').']);
        }

        $kamar->update($data);

        return back()->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function moveResident(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['kamar_id' => ['required', 'exists:kamars,id']]);
        abort_unless($user->role === 'penghuni' && $user->status_penghuni === 'aktif', 404);
        $target = Kamar::findOrFail($data['kamar_id']);
        if ($target->status !== 'tersedia' || $target->penghunis()->where('role', 'penghuni')->count() >= $target->kapasitas) {
            return back()->withErrors(['kamar_id' => 'Kamar tujuan penuh atau sedang tidak tersedia.']);
        }
        $user->update(['kamar_id' => $target->id]);
        $this->refreshOccupancy();
        return back()->with('success', 'Penghuni berhasil dipindahkan.');
    }

    public function removeResident(User $user): RedirectResponse
    {
        abort_unless($user->role === 'penghuni' && $user->status_penghuni === 'aktif', 404);
        $user->update(['kamar_id' => null]);
        $this->refreshOccupancy();
        return back()->with('success', 'Penghuni berhasil dikeluarkan dari kamar.');
    }

    public function index(): View
    {
        $this->refreshOccupancy();
        $query = Kamar::with(['penghunis' => fn ($query) => $query->where('role', 'penghuni')->where('status_penghuni', 'aktif')->orderBy('name')])->withCount(['penghunis' => fn ($query) => $query->where('role', 'penghuni')->where('status_penghuni', 'aktif')])->orderByRaw('CAST(nomor_kamar AS UNSIGNED)');

        match (request('status')) {
            'tersedia' => $query->where('status', 'tersedia')->whereColumn('terisi', '<', 'kapasitas'),
            'hampir_penuh' => $query->where('status', 'tersedia')->whereColumn('terisi', '<', 'kapasitas')->where('terisi', '>', 0),
            'penuh' => $query->where('status', 'tersedia')->whereColumn('terisi', '>=', 'kapasitas'),
            default => null,
        };

        return view('admin.kamar.index', ['kamars' => $query->get()]);
    }

    private function refreshOccupancy(): void
    {
        Kamar::query()->each(function (Kamar $kamar): void {
            $kamar->update(['terisi' => $kamar->penghunis()->where('role', 'penghuni')->where('status_penghuni', 'aktif')->count()]);
        });
    }
}
