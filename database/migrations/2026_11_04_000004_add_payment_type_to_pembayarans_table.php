<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembayarans', 'jenis_pembayaran')) {
            Schema::table('pembayarans', function (Blueprint $table): void {
                $table->string('jenis_pembayaran', 30)->default('iuran_bulanan')->after('user_id');
            });
        }
        if (!Schema::hasColumn('pembayarans', 'nomor_kuitansi')) {
            Schema::table('pembayarans', function (Blueprint $table): void {
                $table->string('nomor_kuitansi', 50)->nullable()->unique()->after('status_pembayaran');
            });
        }
        try {
            Schema::table('pembayarans', function (Blueprint $table): void {
                $table->index('user_id', 'pembayarans_user_id_index');
            });
        } catch (\Throwable) {
        }
        try {
            Schema::table('pembayarans', function (Blueprint $table): void {
                $table->dropUnique(['user_id', 'bulan_tagihan', 'tahun_tagihan']);
            });
        } catch (\Throwable) {
        }
        try {
            Schema::table('pembayarans', function (Blueprint $table): void {
                $table->unique(['user_id', 'jenis_pembayaran', 'bulan_tagihan', 'tahun_tagihan']);
            });
        } catch (\Throwable) {
        }
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'jenis_pembayaran', 'bulan_tagihan', 'tahun_tagihan']);
            $table->dropUnique(['nomor_kuitansi']);
            $table->dropColumn(['jenis_pembayaran', 'nomor_kuitansi']);
            $table->unique(['user_id', 'bulan_tagihan', 'tahun_tagihan']);
        });
    }
};
