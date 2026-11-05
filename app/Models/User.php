<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kamar_id',
        'semester',
        'active',
        'id_asrama',
        'tanggal_masuk',
        'tanggal_berakhir',
        'status_penghuni',
        'tanggal_keluar',
        'alasan_keluar',
    ];

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function laporanKeuangan()
    {
        return $this->hasMany(LaporanKeuangan::class, 'created_by');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_masuk' => 'date',
            'tanggal_berakhir' => 'date',
            'tanggal_keluar' => 'date',
        ];
    }
}
