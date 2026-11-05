<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = ['user_id', 'nomor_pendaftaran', 'nik', 'nama_lengkap', 'tanggal_lahir', 'tempat_lahir', 'agama', 'asal_daerah', 'alamat_asal', 'institusi_pendidikan', 'jenjang', 'fakultas', 'program_studi', 'nomor_induk_mahasiswa', 'semester', 'no_hp_calon', 'no_hp_orang_tua', 'nama_orang_tua', 'pekerjaan_orang_tua', 'kontak_darurat', 'catatan_kesehatan', 'persetujuan', 'status_pendaftaran', 'catatan_admin'];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date', 'persetujuan' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function berkas()
    {
        return $this->hasMany(BerkasPendaftaran::class);
    }
}
