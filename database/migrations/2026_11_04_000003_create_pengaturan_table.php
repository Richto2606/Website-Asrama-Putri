<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table): void {
            $table->id();
            $table->string('kunci')->unique();
            $table->text('nilai')->nullable();
            $table->timestamps();
        });
        DB::table('pengaturan')->insert([
            ['kunci' => 'nominal_registrasi', 'nilai' => '500000', 'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'nominal_iuran_bulanan', 'nilai' => '150000', 'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'bank', 'nilai' => 'Bank BNI', 'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'nomor_rekening', 'nilai' => '', 'created_at' => now(), 'updated_at' => now()],
            ['kunci' => 'atas_nama', 'nilai' => 'Asrama Putri Junjung Buyah', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
