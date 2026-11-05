<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table): void {
            $table->enum('metode_pembayaran', ['cash', 'transfer'])->nullable()->after('nominal');
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table): void {
            $table->dropColumn(['metode_pembayaran', 'bukti_pembayaran']);
        });
    }
};
