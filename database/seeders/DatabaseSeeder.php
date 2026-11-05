<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (range(1, 12) as $nomor) {
            Kamar::create(['nomor_kamar' => (string) $nomor, 'kapasitas' => 2]);
        }

        User::factory()->create([
            'name' => 'Asrama Putri Junjung Buyah Kukar YK',
            'email' => 'admin@siap-kukar.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }
}
