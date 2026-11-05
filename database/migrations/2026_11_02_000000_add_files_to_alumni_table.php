<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table): void {
            $table->json('berkas')->nullable()->after('nomor_kamar');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table): void {
            $table->dropColumn('berkas');
        });
    }
};
