<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('kamars')->update(['kapasitas' => 2]);
    }

    public function down(): void
    {
        DB::table('kamars')->update(['kapasitas' => 4]);
    }
};
