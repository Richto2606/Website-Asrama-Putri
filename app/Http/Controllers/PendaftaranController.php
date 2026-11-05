<?php

namespace App\Http\Controllers;

use App\Models\BerkasPendaftaran;
use App\Models\Kamar;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function create(): View
    {
        $availableRooms = Kamar::where('status', 'tersedia')->whereColumn('terisi', '<', 'kapasitas')->count();
        $registrationOpen = Pengaturan::value('status_pendaftaran', 'buka') === 'buka';

        return view('pendaftaran.create', compact('availableRooms', 'registrationOpen'));
    }

    public function statusForm(): View
    {
        return view('pendaftaran.status');
    }

    public function status(Request $request): View
    {
        $credentials = $request->validate(['nomor_pendaftaran' => ['required', 'string'], 'email' => ['required', 'email']]);
        $pendaftaran = Pendaftaran::with('user')->where('nomor_pendaftaran', $credentials['nomor_pendaftaran'])->whereHas('user', fn ($query) => $query->where('email', $credentials['email']))->first();
        if (!$pendaftaran) {
            return view('pendaftaran.status')->withErrors(['nomor_pendaftaran' => 'Nomor pendaftaran atau email tidak ditemukan.']);
        }

        return view('pendaftaran.status', compact('pendaftaran'));
    }

    public function template(string $type)
    {
        $templates = ['surat-permohonan' => 'Surat Permohonan Pendaftaran Asrama', 'persetujuan-orang-tua' => 'Surat Persetujuan Orang Tua', 'surat-pernyataan' => 'Surat Pernyataan Bermaterai'];
        abort_unless(isset($templates[$type]), 404);
        return response()->view('pendaftaran.template', ['title' => $templates[$type]])->header('Content-Disposition', 'attachment; filename="'.$type.'.html"');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Pengaturan::value('status_pendaftaran', 'buka') === 'tutup') {
            throw ValidationException::withMessages(['pendaftaran' => 'Pendaftaran sedang ditutup oleh admin.']);
        }
        if (Kamar::whereColumn('terisi', '<', 'kapasitas')->count() < 1) {
            throw ValidationException::withMessages(['pendaftaran' => 'Pendaftaran ditutup karena seluruh kamar sedang penuh.']);
        }

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'agama' => ['required', 'string', 'max:50'],
            'nik' => ['required', 'digits:16', 'unique:pendaftarans,nik'],
            'asal_daerah' => ['required', 'in:Kutai Kartanegara'],
            'alamat_asal' => ['required', 'string', 'max:255'],
            'institusi_pendidikan' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'string', 'max:50'],
            'fakultas' => ['required', 'string', 'max:255'],
            'program_studi' => ['required', 'string', 'max:255'],
            'nomor_induk_mahasiswa' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'no_hp_calon' => ['required', 'string', 'max:20'],
            'no_hp_orang_tua' => ['required', 'string', 'max:20'],
            'nama_orang_tua' => ['required', 'string', 'max:255'],
            'pekerjaan_orang_tua' => ['required', 'string', 'max:255'],
            'kontak_darurat' => ['required', 'string', 'max:255'],
            'catatan_kesehatan' => ['nullable', 'string', 'max:1000'],
            'persetujuan' => ['accepted'],
            'surat_permohonan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_persetujuan_orang_tua' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'ktp_kia' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'kartu_keluarga' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'ktm_kartu_pelajar' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_aktif_kuliah' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_diterima' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_rekomendasi_kesra' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_penghasilan_orang_tua' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_kesehatan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'pas_foto' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_pernyataan_bermaterai' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $pendaftaran = null;
        DB::transaction(function () use ($request, $validated, &$pendaftaran): void {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Str::random(32),
                'role' => 'pendaftar',
            ]);

            $pendaftaran = Pendaftaran::create([
                'user_id' => $user->id,
                'nomor_pendaftaran' => 'APJB-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'agama' => $validated['agama'],
                'asal_daerah' => $validated['asal_daerah'],
                'alamat_asal' => $validated['alamat_asal'],
                'institusi_pendidikan' => $validated['institusi_pendidikan'],
                'jenjang' => $validated['jenjang'],
                'fakultas' => $validated['fakultas'],
                'program_studi' => $validated['program_studi'],
                'nomor_induk_mahasiswa' => $validated['nomor_induk_mahasiswa'],
                'semester' => $validated['semester'],
                'no_hp_calon' => $validated['no_hp_calon'],
                'no_hp_orang_tua' => $validated['no_hp_orang_tua'],
                'nama_orang_tua' => $validated['nama_orang_tua'],
                'pekerjaan_orang_tua' => $validated['pekerjaan_orang_tua'],
                'kontak_darurat' => $validated['kontak_darurat'],
                'catatan_kesehatan' => $validated['catatan_kesehatan'] ?? null,
                'persetujuan' => true,
                'status_pendaftaran' => 'menunggu',
            ]);

            foreach (['surat_permohonan', 'surat_persetujuan_orang_tua', 'ktp_kia', 'kartu_keluarga', 'ktm_kartu_pelajar', 'surat_aktif_kuliah', 'surat_diterima', 'surat_rekomendasi_kesra', 'surat_penghasilan_orang_tua', 'surat_kesehatan', 'pas_foto', 'surat_pernyataan_bermaterai'] as $jenis) {
                BerkasPendaftaran::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'jenis_berkas' => $jenis,
                    'file_path' => $request->file($jenis)->store('pendaftaran/'.$pendaftaran->id, 'public'),
                ]);
            }
        });

        return to_route('pendaftaran.create')->with('success', 'Terima kasih sudah mendaftar')->with('registration_number', $pendaftaran->nomor_pendaftaran);
    }
}
