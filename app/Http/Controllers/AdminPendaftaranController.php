<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminPendaftaranController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $search = trim((string) $request->query('search', ''));
        $campus = trim((string) $request->query('campus', ''));
        $query = Pendaftaran::with(['berkas', 'user'])->latest();

        if ($status === 'pending') {
            $query->where('status_pendaftaran', 'menunggu');
        } elseif ($status === 'riwayat') {
            $query->whereIn('status_pendaftaran', ['diterima', 'ditolak', 'perlu_perbaikan', 'daftar_tunggu']);
        } elseif ($status !== 'semua') {
            $query->where('status_pendaftaran', $status);
        }

        if ($campus !== '') {
            $query->where('institusi_pendidikan', $campus);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('asal_daerah', 'like', "%{$search}%")
                    ->orWhere('institusi_pendidikan', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($query) => $query->where('email', 'like', "%{$search}%"));
            });
        }

        return view('admin.pendaftaran.index', [
            'pendaftarans' => $query->paginate(15)->withQueryString(),
            'pendingCount' => Pendaftaran::where('status_pendaftaran', 'menunggu')->count(),
            'historyCount' => Pendaftaran::whereIn('status_pendaftaran', ['diterima', 'ditolak', 'perlu_perbaikan', 'daftar_tunggu'])->count(),
            'status' => $status,
            'search' => $search,
            'campus' => $campus,
            'campuses' => Pendaftaran::query()->whereNotNull('institusi_pendidikan')->distinct()->orderBy('institusi_pendidikan')->pluck('institusi_pendidikan'),
        ]);
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        return view('admin.pendaftaran.show', [
            'pendaftaran' => $pendaftaran->load(['berkas', 'user']),
        ]);
    }

    public function update(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'status_pendaftaran' => ['required', 'in:diterima,ditolak,perlu_perbaikan,daftar_tunggu'],
            'catatan_admin' => ['required', 'string', 'max:1000'],
        ]);
        $status = $validated['status_pendaftaran'];

        DB::transaction(function () use ($pendaftaran, $status, $validated): void {
            $pendaftaran->update(['status_pendaftaran' => $status, 'catatan_admin' => $validated['catatan_admin']]);
            $pendaftaran->user()->update([
                'role' => $status === 'diterima' ? 'penghuni' : 'pendaftar',
                'semester' => $status === 'diterima' ? $pendaftaran->semester : null,
            ]);
        });

        return back()->with('success', 'Status pendaftaran diperbarui. Pendaftar dapat melihatnya melalui halaman cek status.');
    }
}
