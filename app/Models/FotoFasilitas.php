<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoFasilitas extends Model
{
    protected $fillable = ['nama_fasilitas', 'keterangan', 'file_path', 'terlihat'];

    protected function casts(): array
    {
        return ['terlihat' => 'boolean'];
    }
}
