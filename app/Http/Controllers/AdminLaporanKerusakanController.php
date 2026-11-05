<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminLaporanKerusakanController extends Controller
{
    public function index(): View
    {
        return view('admin.laporan-kerusakan.index', [
            'laporan' => LaporanKerusakan::with('creator')->latest()->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $data['gambar'] = $request->file('gambar')->store('kerusakan', 'public');
        $data['created_by'] = Auth::id();
        LaporanKerusakan::create($data);

        return back()->with('success', 'Laporan kerusakan berhasil ditambahkan.');
    }

    public function destroy(LaporanKerusakan $laporanKerusakan): RedirectResponse
    {
        Storage::disk('public')->delete($laporanKerusakan->gambar);
        $laporanKerusakan->delete();

        return back()->with('success', 'Laporan kerusakan berhasil dihapus.');
    }

    public function exportPdf(): Response
    {
        $laporan = LaporanKerusakan::with('creator')->latest()->get();
        $pdf = Pdf::loadView('admin.laporan-kerusakan.pdf', compact('laporan'))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-kerusakan-asrama-'.now()->format('YmdHis').'.pdf');
    }
}
