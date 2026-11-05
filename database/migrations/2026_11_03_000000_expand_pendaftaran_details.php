<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->string('nomor_pendaftaran')->nullable()->unique();
            $table->string('tempat_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('no_hp_calon')->nullable();
            $table->string('jenjang')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('nomor_induk_mahasiswa')->nullable();
            $table->string('pekerjaan_orang_tua')->nullable();
            $table->string('kontak_darurat')->nullable();
            $table->text('catatan_kesehatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->boolean('persetujuan')->default(false);
            $table->string('status_pendaftaran')->default('menunggu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->dropUnique(['nomor_pendaftaran']);
            $table->dropColumn(['nomor_pendaftaran', 'tempat_lahir', 'agama', 'no_hp_calon', 'jenjang', 'fakultas', 'program_studi', 'nomor_induk_mahasiswa', 'pekerjaan_orang_tua', 'kontak_darurat', 'catatan_kesehatan', 'catatan_admin', 'persetujuan']);
            $table->enum('status_pendaftaran', ['pending', 'diterima', 'ditolak'])->default('pending')->change();
        });
    }
};
