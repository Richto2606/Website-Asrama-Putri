<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','admin','bendahara','sekretaris','penghuni','pendaftar') NOT NULL DEFAULT 'pendaftar'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('superadmin','admin','bendahara','penghuni','pendaftar') NOT NULL DEFAULT 'pendaftar'");
        }
    }
};
