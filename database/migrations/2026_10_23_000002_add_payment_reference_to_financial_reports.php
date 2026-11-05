<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table): void {
            $table->foreignId('pembayaran_id')->nullable()->unique()->after('created_by')->constrained('pembayarans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table): void {
            $table->dropForeign(['pembayaran_id']);
            $table->dropColumn('pembayaran_id');
        });
    }
};
