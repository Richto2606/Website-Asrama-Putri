<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamars', function (Blueprint $table): void {
            $table->string('nama_kamar')->nullable()->after('nomor_kamar');
            $table->string('status', 30)->default('tersedia')->after('terisi');
        });
    }

    public function down(): void
    {
        Schema::table('kamars', function (Blueprint $table): void {
            $table->dropColumn(['nama_kamar', 'status']);
        });
    }
};
