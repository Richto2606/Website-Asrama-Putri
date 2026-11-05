<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_fasilitas', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_fasilitas');
            $table->text('keterangan')->nullable();
            $table->string('file_path');
            $table->boolean('terlihat')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_fasilitas');
    }
};
