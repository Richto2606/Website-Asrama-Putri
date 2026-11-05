<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table): void {
            $table->string('kategori', 40)->default('lain_lain')->after('jenis_transaksi');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table): void {
            $table->dropColumn('kategori');
        });
    }
};
