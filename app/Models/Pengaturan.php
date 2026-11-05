<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $fillable = ['kunci', 'nilai'];

    public static function value(string $key, mixed $default = null): mixed
    {
        return static::where('kunci', $key)->value('nilai') ?? $default;
    }
}
