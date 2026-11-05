<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('id_asrama', 50)->nullable()->unique()->after('id');
            $table->date('tanggal_masuk')->nullable()->after('id_asrama');
            $table->date('tanggal_berakhir')->nullable()->after('tanggal_masuk');
            $table->string('status_penghuni', 20)->default('aktif')->after('tanggal_berakhir');
            $table->date('tanggal_keluar')->nullable()->after('status_penghuni');
            $table->text('alasan_keluar')->nullable()->after('tanggal_keluar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['id_asrama']);
            $table->dropColumn(['id_asrama', 'tanggal_masuk', 'tanggal_berakhir', 'status_penghuni', 'tanggal_keluar', 'alasan_keluar']);
        });
    }
};
