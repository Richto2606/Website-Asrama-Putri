<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table): void {
            $table->text('testimoni')->nullable()->after('alasan_keluar');
            $table->boolean('testimoni_aktif')->default(false)->after('testimoni');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table): void {
            $table->dropColumn(['testimoni', 'testimoni_aktif']);
        });
    }
};
