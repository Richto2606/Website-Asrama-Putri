<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('nik', 16)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('asal_daerah')->nullable();
            $table->string('alamat_asal')->nullable();
            $table->string('institusi_pendidikan')->nullable();
            $table->unsignedTinyInteger('semester')->nullable();
            $table->string('no_hp_orang_tua')->nullable();
            $table->string('nama_orang_tua')->nullable();
            $table->string('nomor_kamar')->nullable();
            $table->timestamp('bergabung_sebagai_alumni_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
