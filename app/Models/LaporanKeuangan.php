<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    protected $fillable = ['jenis_transaksi', 'kategori', 'nominal', 'metode_pembayaran', 'bukti_pembayaran', 'keterangan', 'tanggal', 'created_by', 'pembayaran_id'];

    protected function casts(): array
    {
        return ['tanggal' => 'date', 'nominal' => 'decimal:2'];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
