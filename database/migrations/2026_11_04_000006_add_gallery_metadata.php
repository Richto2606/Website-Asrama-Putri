<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table): void {
            $table->date('tanggal')->nullable()->after('keterangan');
            $table->string('kategori', 30)->default('kegiatan')->after('tanggal');
            $table->boolean('terlihat')->default(true)->after('kategori');
            $table->boolean('unggulan')->default(false)->after('terlihat');
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table): void {
            $table->dropColumn(['tanggal', 'kategori', 'terlihat', 'unggulan']);
        });
    }
};
