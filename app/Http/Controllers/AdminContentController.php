<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminContentController extends Controller
{
    public function index(): View
    {
        return view('admin.pengaturan', ['pengumumans' => Pengumuman::latest()->get(), 'settings' => Pengaturan::pluck('nilai', 'kunci')]);
    }

    public function announcement(Request $request): RedirectResponse
    {
        Pengumuman::create($request->validate(['judul' => ['required', 'string', 'max:255'], 'isi' => ['required', 'string', 'max:2000']]));
        return back()->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function destroyAnnouncement(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function settings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status_pendaftaran' => ['required', 'in:buka,tutup'],
            'formulir_pendaftaran' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'nominal_iuran_bulanan' => ['required', 'numeric', 'min:0'],
            'bank' => ['required', 'string', 'max:100'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'atas_nama' => ['required', 'string', 'max:150'],
            'alamat_asrama' => ['required', 'string', 'max:500'],
            'kontak_humas' => ['required', 'string', 'max:100'],
        ]);
        if ($request->hasFile('formulir_pendaftaran')) {
            $oldPath = Pengaturan::value('formulir_pendaftaran');
            if ($oldPath) Storage::disk('public')->delete($oldPath);
            $data['formulir_pendaftaran'] = $request->file('formulir_pendaftaran')->store('formulir', 'public');
        } else {
            unset($data['formulir_pendaftaran']);
        }

        foreach ($data as $key => $value) Pengaturan::updateOrCreate(['kunci' => $key], ['nilai' => $value]);
        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
