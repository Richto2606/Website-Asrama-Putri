<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['judul', 'keterangan', 'tanggal', 'kategori', 'jenis', 'file_path', 'terlihat', 'unggulan'];

    protected function casts(): array { return ['tanggal' => 'date', 'terlihat' => 'boolean', 'unggulan' => 'boolean']; }
}
