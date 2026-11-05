<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Models\BerkasPendaftaran;
use App\Models\Kamar;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumniTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_resident_moves_snapshot_to_alumni(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kamar = Kamar::create(['nomor_kamar' => '1', 'kapasitas' => 2]);
        $resident = User::factory()->create([
            'name' => 'Siti Alumni',
            'role' => 'penghuni',
            'semester' => 8,
            'kamar_id' => $kamar->id,
        ]);
        $pendaftaran = Pendaftaran::create([
            'user_id' => $resident->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Siti Alumni',
            'tanggal_lahir' => '2000-01-01',
            'asal_daerah' => 'Kutai Kartanegara',
            'alamat_asal' => 'Tenggarong',
            'institusi_pendidikan' => 'Universitas Kukar',
            'semester' => 8,
            'no_hp_orang_tua' => '08123456789',
            'nama_orang_tua' => 'Orang Tua',
            'status_pendaftaran' => 'diterima',
        ]);
        BerkasPendaftaran::create(['pendaftaran_id' => $pendaftaran->id, 'jenis_berkas' => 'surat_aktif_kuliah', 'file_path' => 'pendaftaran/1/aktif.pdf']);
        BerkasPendaftaran::create(['pendaftaran_id' => $pendaftaran->id, 'jenis_berkas' => 'surat_rekomendasi_kesra', 'file_path' => 'pendaftaran/1/rekomendasi.pdf']);
        BerkasPendaftaran::create(['pendaftaran_id' => $pendaftaran->id, 'jenis_berkas' => 'surat_kesehatan', 'file_path' => 'pendaftaran/1/kesehatan.pdf']);
        BerkasPendaftaran::create(['pendaftaran_id' => $pendaftaran->id, 'jenis_berkas' => 'surat_skck', 'file_path' => 'pendaftaran/1/skck.pdf']);

        $response = $this->actingAs($admin)->delete(route('admin.penghuni.destroy', $resident));

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $resident->id]);
        $this->assertDatabaseHas('alumni', [
            'name' => 'Siti Alumni',
            'email' => $resident->email,
            'nik' => '1234567890123456',
            'nomor_kamar' => '1',
        ]);
        $this->assertSame([
            ['jenis' => 'surat_aktif_kuliah', 'file_path' => 'pendaftaran/1/aktif.pdf'],
            ['jenis' => 'surat_rekomendasi_kesra', 'file_path' => 'pendaftaran/1/rekomendasi.pdf'],
            ['jenis' => 'surat_kesehatan', 'file_path' => 'pendaftaran/1/kesehatan.pdf'],
            ['jenis' => 'surat_skck', 'file_path' => 'pendaftaran/1/skck.pdf'],
        ], Alumni::firstOrFail()->berkas);
    }
}
