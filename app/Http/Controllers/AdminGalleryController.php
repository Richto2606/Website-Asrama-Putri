<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminGalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', ['galleries' => Gallery::latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'tanggal' => ['nullable', 'date'],
            'kategori' => ['required', 'in:fasilitas,kamar,kegiatan'],
            'jenis' => ['required', 'in:foto,video'],
            'file' => ['required_if:jenis,foto', 'nullable', 'array', 'max:30'],
            'file.*' => ['file', 'max:51200', 'mimes:jpg,jpeg,png,webp'],
            'video_url' => ['required_if:jenis,video', 'nullable', 'url', 'starts_with:https://', 'max:2000'],
        ]);

        $common = ['judul' => $data['judul'], 'keterangan' => $data['keterangan'] ?? null, 'tanggal' => $data['tanggal'] ?? now()->toDateString(), 'kategori' => $data['kategori'], 'jenis' => $data['jenis'], 'terlihat' => true, 'unggulan' => false];
        if ($data['jenis'] === 'foto') {
            foreach ($request->file('file', []) as $file) {
                Gallery::create([...$common, 'file_path' => $file->store('gallery', 'public')]);
            }
        } else {
            Gallery::create([...$common, 'file_path' => $data['video_url']]);
        }

        return back()->with('success', 'Media gallery berhasil ditambahkan.');
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'tanggal' => ['nullable', 'date'],
            'kategori' => ['required', 'in:fasilitas,kamar,kegiatan'],
            'terlihat' => ['nullable', 'boolean'],
            'unggulan' => ['nullable', 'boolean'],
            'jenis' => ['required', 'in:foto,video'],
            'file' => ['nullable', 'file', 'max:51200', 'mimes:jpg,jpeg,png,webp'],
            'video_url' => ['nullable', 'url', 'starts_with:https://', 'max:2000'],
        ]);

        abort_if($data['jenis'] !== $gallery->jenis && ! $request->hasFile('file') && ! filled($data['video_url'] ?? null), 422, 'Media baru wajib diisi saat mengganti jenis media.');

        $filePath = $gallery->file_path;
        if ($data['jenis'] === 'foto' && $request->hasFile('file')) {
            if ($gallery->jenis === 'foto') {
                Storage::disk('public')->delete($gallery->file_path);
            }
            $filePath = $request->file('file')->store('gallery', 'public');
        } elseif ($data['jenis'] === 'video' && filled($data['video_url'] ?? null)) {
            if ($gallery->jenis === 'foto') {
                Storage::disk('public')->delete($gallery->file_path);
            }
            $filePath = $data['video_url'];
        }

        $gallery->update([
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'] ?? null,
            'tanggal' => $data['tanggal'] ?? $gallery->tanggal,
            'kategori' => $data['kategori'],
            'terlihat' => $request->boolean('terlihat'),
            'unggulan' => $request->boolean('unggulan'),
            'jenis' => $data['jenis'],
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Media gallery berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        if ($gallery->jenis === 'foto') {
            Storage::disk('public')->delete($gallery->file_path);
        }
        $gallery->delete();

        return back()->with('success', 'Media gallery berhasil dihapus.');
    }
}
