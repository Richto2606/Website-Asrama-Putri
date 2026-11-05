<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasPendaftaran extends Model
{
    protected $fillable = ['pendaftaran_id', 'jenis_berkas', 'file_path'];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
