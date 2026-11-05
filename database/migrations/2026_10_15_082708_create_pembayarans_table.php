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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan_tagihan');
            $table->unsignedSmallInteger('tahun_tagihan');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('status_pembayaran', ['menunggu_verifikasi', 'lunas', 'ditolak'])->default('menunggu_verifikasi');
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'bulan_tagihan', 'tahun_tagihan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
