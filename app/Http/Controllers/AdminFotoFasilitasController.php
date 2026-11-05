<?php

namespace App\Http\Controllers;

use App\Models\FotoFasilitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminFotoFasilitasController extends Controller
{
    public function index(): View
    {
        return view('admin.foto-fasilitas.index', ['fotoFasilitas' => FotoFasilitas::latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'file' => ['required', 'array', 'max:30'],
            'file.*' => ['file', 'max:51200', 'mimes:jpg,jpeg,png,webp'],
        ]);

        foreach ($request->file('file', []) as $file) {
            FotoFasilitas::create([
                'nama_fasilitas' => $data['nama_fasilitas'],
                'keterangan' => $data['keterangan'] ?? null,
                'file_path' => $file->store('foto-fasilitas', 'public'),
                'terlihat' => true,
            ]);
        }

        return back()->with('success', 'Foto fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, FotoFasilitas $fotoFasilitas): RedirectResponse
    {
        $data = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'max:51200', 'mimes:jpg,jpeg,png,webp'],
            'terlihat' => ['nullable', 'boolean'],
        ]);

        $filePath = $fotoFasilitas->file_path;
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($filePath);
            $filePath = $request->file('file')->store('foto-fasilitas', 'public');
        }

        $fotoFasilitas->update([
            'nama_fasilitas' => $data['nama_fasilitas'],
            'keterangan' => $data['keterangan'] ?? null,
            'file_path' => $filePath,
            'terlihat' => $request->boolean('terlihat'),
        ]);

        return back()->with('success', 'Foto fasilitas berhasil diperbarui.');
    }

    public function destroy(FotoFasilitas $fotoFasilitas): RedirectResponse
    {
        Storage::disk('public')->delete($fotoFasilitas->file_path);
        $fotoFasilitas->delete();

        return back()->with('success', 'Foto fasilitas berhasil dihapus.');
    }
}
