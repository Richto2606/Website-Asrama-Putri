<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAlumniController extends Controller
{
    public function index(): View
    {
        return view('admin.alumni.index', [
            'alumnis' => Alumni::latest('bergabung_sebagai_alumni_pada')->paginate(15),
        ]);
    }

    public function show(Alumni $alumni): View
    {
        return view('admin.alumni.show', compact('alumni'));
    }

    public function updateTestimonial(Request $request, Alumni $alumni): RedirectResponse
    {
        $data = $request->validate([
            'testimoni' => ['nullable', 'string', 'max:1000'],
            'testimoni_aktif' => ['nullable', 'boolean'],
        ]);

        $alumni->update([
            'testimoni' => $data['testimoni'] ?? null,
            'testimoni_aktif' => $request->boolean('testimoni_aktif') && filled($data['testimoni'] ?? null),
        ]);

        return to_route('admin.alumni.show', $alumni)->with('success', 'Testimoni alumni berhasil disimpan.');
    }
}
