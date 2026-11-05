<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table): void {
            $table->string('status_penghuni', 20)->default('alumni')->after('nomor_kamar');
            $table->date('tanggal_masuk')->nullable()->after('status_penghuni');
            $table->date('tanggal_keluar')->nullable()->after('tanggal_masuk');
            $table->text('alasan_keluar')->nullable()->after('tanggal_keluar');
            $table->string('id_asrama', 50)->nullable()->after('alasan_keluar');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table): void {
            $table->dropColumn(['status_penghuni', 'tanggal_masuk', 'tanggal_keluar', 'alasan_keluar', 'id_asrama']);
        });
    }
};
