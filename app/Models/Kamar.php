<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $fillable = ['nomor_kamar', 'nama_kamar', 'kapasitas', 'terisi', 'status'];

    public function penghunis()
    {
        return $this->hasMany(User::class);
    }
}
