<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('kamar_id')->nullable()->after('role')->constrained('kamars')->nullOnDelete();
            $table->unsignedTinyInteger('semester')->nullable()->after('kamar_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['kamar_id']);
            $table->dropColumn(['kamar_id', 'semester']);
        });
    }
};
