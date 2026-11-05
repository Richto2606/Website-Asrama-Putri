<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';

    protected $fillable = [
        'name',
        'email',
        'nik',
        'tanggal_lahir',
        'asal_daerah',
        'alamat_asal',
        'institusi_pendidikan',
        'semester',
        'no_hp_orang_tua',
        'nama_orang_tua',
            'nomor_kamar',
            'status_penghuni',
            'tanggal_masuk',
            'tanggal_keluar',
            'alasan_keluar',
            'testimoni',
            'testimoni_aktif',
            'id_asrama',
            'berkas',
        'bergabung_sebagai_alumni_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'bergabung_sebagai_alumni_pada' => 'datetime',
            'tanggal_masuk' => 'date',
            'tanggal_keluar' => 'date',
            'berkas' => 'array',
            'testimoni_aktif' => 'boolean',
        ];
    }
}
