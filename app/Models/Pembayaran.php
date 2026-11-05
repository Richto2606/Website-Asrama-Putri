<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = ['user_id', 'jenis_pembayaran', 'bulan_tagihan', 'tahun_tagihan', 'jumlah_bayar', 'metode_pembayaran', 'status_pembayaran', 'bukti_transfer', 'nomor_kuitansi'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
