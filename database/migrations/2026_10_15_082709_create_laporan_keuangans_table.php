<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->decimal('nominal', 12, 2);
            $table->string('keterangan');
            $table->date('tanggal');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangans');
    }
};
