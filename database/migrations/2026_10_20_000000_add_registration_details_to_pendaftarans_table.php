<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE berkas_pendaftarans MODIFY jenis_berkas VARCHAR(255) NOT NULL');
        }

        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->string('alamat_asal')->nullable();
            $table->unsignedTinyInteger('semester')->nullable();
            $table->string('no_hp_orang_tua')->nullable();
            $table->string('nama_orang_tua')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table): void {
            $table->dropColumn(['alamat_asal', 'semester', 'no_hp_orang_tua', 'nama_orang_tua']);
        });
    }
};
